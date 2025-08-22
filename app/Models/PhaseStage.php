<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{Phase, PhaseStageParameter,StageOperation, Stage, OperationLedgerEntry,Shaft};
use App\Services\EngravingOrderService;
use App\Events\StageUpdated;

class PhaseStage extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $preCopper = false;

    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    public static function statuses(): array
    {
        return [
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_REJECTED => 'Брак',
            self::STATUS_COMPLETED => 'Завершен',
        ];
    }

    public function getStatusShaft(): string
    {
        return self::statuses()[$this->status] ?? 'Неизвестный статус';
    }

    public function phase()
    {
        return $this->belongsTo(Phase::class,'phase_id');
    }

    public function parameters()
    {
        return $this->hasMany(PhaseStageParameter::class,'phase_stage_id');
    }

    public function stage()
    {
        return $this->hasOne(Stage::class,'id','stage_id');
    }

    public function operation()
    {
        return $this->hasOne(OperationLedgerEntry::class,'phase_stage_id','id');
    }

    public function operations()
    {
        return $this->hasMany(StageOperation::class,'stage_id','stage_id');
    }

    public function engravingOrderShaft()
    {
        return $this->hasOneThrough(
            EngravingOrderShaft::class,
            Phase::class,
            'id', // Foreign key on the cars table...
            'id', // Foreign key on the owners table...
            'phase_id', // Local key on the mechanics table...
            'engraving_order_shaft_id' // Local key on the cars table...
        );
    }

    public function movementOrder()
    {
        return $this->hasOneThrough(MovementOrder::class, EngravingOrderShaft::class, 'engraving_order_id', 'engraving_order_id');
    }

    protected static function booted()
    {
        static::updated(function ($phaseStage) {
            broadcast(new StageUpdated($phaseStage));
            if ($phaseStage->status === self::STATUS_COMPLETED) {

                $phaseStage->phase->stages()
                ->where('sequence', '>', $phaseStage->sequence)
                ->get()
                ->each->createPlanParameters();

                if ($phaseStage->stage->name == 'Входной контроль') {
                    Shaft::where('id', $phaseStage->engravingOrderShaft->shaft_id)->update(['state' => 'engraving']);
                }
            }
        });

        static::created(fn($phaseStage) => $phaseStage->createPlanParameters());
    }

    public function createPlanParameters()
    {
        $parameters = $this->getParametersForStage();

        if (!empty($parameters)) {
            array_map(fn($param) => PhaseStageParameter::updateOrCreate(
                ['phase_stage_id' => $this->id, 'parameter_id' => $param['parameter_id']],
                ['float_value' => $param['float_value'] ?? null, 'string_value' => $param['string_value'] ?? null]
            ), $parameters);
        }


    }

    public function calculateCopperPlating($engravingOrderShaft, $startDiameter, $endDiameter)
    {
        $copperPlating =(($engravingOrderShaft->final_diameter - $engravingOrderShaft->shaft->diameter) * 1000 * 1.05) / 2;

        if ($copperPlating > 150) {
            $copperPlating += $this->getCopperReserve($engravingOrderShaft->engravingOrder->format);
        }

        return $copperPlating;
    }

    function calculateBaseGrindingDiameter($stage)
    {
        $this->engravingOrderShaft->load('shaft','engravingOrder');

        $copperReserve = $this->getCopperReserve($this->engravingOrderShaft->engravingOrder->format);
        // Определяем максимальное меднение на один проход
        $maxPlating = ($this->engravingOrderShaft->shaft->type === 'Хром' || $this->engravingOrderShaft->shaft->type === 'Медь') ? 500 : 350;

        // Считаем общее меднение
        $totalPlating = ($this->engravingOrderShaft->final_diameter - ($this->engravingOrderShaft->shaft->diameter - 0.144)) * 1000 * 1.05 / 2;
        $platingStages = [];

        // Для стального вала всегда минимум 2 этапа
        if ($this->engravingOrderShaft->shaft->type === 'Сталь' && $totalPlating <= $maxPlating) {
            $firstLayer = max($totalPlating - 70, 70);
            $platingStages = [$firstLayer, $totalPlating - $firstLayer];
        } else {
            // Разбиваем меднение на этапы с учетом ограничений
            while ($totalPlating > 0) {
                $currentPlating = min($maxPlating, $totalPlating);
                if ($totalPlating - $currentPlating < 70 && $totalPlating > $maxPlating) {
                    $currentPlating = $totalPlating - 70;
                }
                $platingStages[] = $currentPlating;
                $totalPlating -= $currentPlating;
            }
        }


        // Рассчитываем диаметр шлифовки базы на нужном этапе
        $baseDiameter = $this->engravingOrderShaft->shaft->diameter - 0.144;
        for ($i = 1; $i < $stage && isset($platingStages[$i - 1]); $i++) {
            $baseDiameter += (2 * ($platingStages[$i - 1] / 1000)) - $copperReserve/1000*2;
        }

        return round($baseDiameter, 3);
    }

    function calculatePlatingOnSpecificStage($stage)
    {
        $copperReserve = $this->getCopperReserve($this->engravingOrderShaft->engravingOrder->format);
        // Определяем максимальное меднение за один проход
        $maxPlating = ($this->engravingOrderShaft->shaft->type === 'Хром' || $this->engravingOrderShaft->shaft->type === 'Медь') ? 500 : 350;
    
        // Считаем общее меднение (в мкм)
        $totalPlating = ($this->engravingOrderShaft->final_diameter - ($this->engravingOrderShaft->shaft->diameter - 0.144)) * 1000 * 1.05 / 2;
        $platingStages = [];
    
        // Если стальной вал и общее меднение меньше 350 мкм, делим на 2 этапа
        if ($this->engravingOrderShaft->shaft->type === 'Сталь' && $totalPlating <= $maxPlating) {
            $firstLayer = max($totalPlating - 70, 70);
            $platingStages = [$firstLayer, $totalPlating - $firstLayer];
        } else {
            
            if ($totalPlating < 70) {
                $totalPlating = 70;
            }

            while ($totalPlating > 0) {
                $currentPlating = min($maxPlating, $totalPlating);
                if ($totalPlating - $currentPlating < 70 && $totalPlating > $maxPlating) {
                    $currentPlating = $totalPlating - 70;
                }
                
                $platingStages[] = $currentPlating;
                $totalPlating -= $currentPlating;
            }
        }
    
        // Проверяем, существует ли указанный этап
        return isset($platingStages[($this->engravingOrderShaft->phases->count() - 1) - 1]) ? $platingStages[($this->engravingOrderShaft->phases->count() - 1) - 1] : null;
    }

    private function getCopperReserve(int $format): int
    {
        $copperReserves = [
            500 => 35,
            600 => 38,
            700 => 40,
            800 => 43,
            900 => 45,
        ];

        return collect($copperReserves)
            ->filter(fn($value, $key) => $format <= $key)
            ->first() ?? 0;
    }

    private function getParametersForStage(): array
    {
        return match ($this->stage_id) {
            14 => [
                ['parameter_id' => 1, 'float_value' => $this->engravingOrderShaft->load('shaft')->shaft->diameter ?? null],
                ['parameter_id' => 5, 'float_value' => $this->engravingOrderShaft->load('shaft')->shaft->thickness ?? null],
                ['parameter_id' => 20, 'string_value' => $this->engravingOrderShaft->load('shaft')->shaft->type ?? null],
            ],
            3 => [
                ['parameter_id' => 1, 'float_value' => round($this->calculateBaseGrindingDiameter($this->phase->sequence),3)],
                ['parameter_id' => 3, 'string_value' => ($this->engravingOrderShaft->load('shaft')->shaft->type == 'Сталь' && $this->phase->sequence == 1) ? '9...10' : '0,6...0,8'],
            ],
            6 => [['parameter_id' => 5, 'float_value' => round($this->calculatePlatingOnSpecificStage($this->phase->sequence))]],
            7 => [
                ['parameter_id' => 1, 'float_value' => $this->engravingOrderShaft->final_diameter],
                ['parameter_id' => 4, 'string_value' => '205...220'],
                ['parameter_id' => 3, 'string_value' => $this->isColdSeal() ? '1,1...1,2' : '0,5...0,6'],
            ],
            9 => [
                ['parameter_id' => 8, 'string_value' => $this->engravingOrderShaft->lineature],
                ['parameter_id' => 9, 'string_value' => $this->engravingOrderShaft->corner],
                ['parameter_id' => 10, 'string_value' => $this->engravingOrderShaft->cutter],
                ['parameter_id' => 11, 'float_value' => $this->engravingOrderShaft->separation],
                ['parameter_id' => 7, 'string_value' => $this->engravingOrderShaft->color],
            ],
            10 => [['parameter_id' => 5, 'float_value' => 7]],
            12 => [['parameter_id' => 5, 'float_value' => round($this->calculatePlatingOnSpecificStage($this->phase->sequence))]],
            13 => [
                ['parameter_id' => 7, 'string_value' => $this->engravingOrderShaft->color],
                ['parameter_id' => 3, 'string_value' => $this->isColdSeal() ? '0,8...0,9' : '0,4...0,5'],
            ],
            default => [],
        };
    }

    public function moveToNextStage()
    {
        $this->update(['status' => self::STATUS_COMPLETED]);

        $nextStage = self::where('phase_id', $this->phase_id)
            ->where('sequence', '>', $this->sequence)
            ->orderBy('sequence')
            ->first();

        $nextStage
            ? $nextStage->update(['status' => self::STATUS_IN_PROGRESS])
            : $this->phase->update(['status' => 'completed']);
    }

    private function isColdSeal(): bool
    {
        return in_array($this->engravingOrderShaft->color, ['ColdSeal', 'Cold seal']);
    }
}
