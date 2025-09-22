<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Shaft,Order, EngravingOrder, Material, ShrinkageRule, Phase,OperationLedgerEntry,ShaftResource};

class EngravingOrderShaft extends Model
{
    use HasFactory;
    protected $guarded = ['id','sequence_number'];

    protected $fillable = [
        'sequence_number', 'parameters', 'color','engraving_order_id','shaft_id','status_id','lineature','corner','cutter','engraving_time','diameter','note','status'
    ];

    protected $casts = [
        'parameters' => 'array',
    ];

    protected $appends = ['separation'];

    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_WRITTEN_OFF = 'written_off';
    const STATUS_REPLACED = 'replaced';

    public static function statuses(): array
    {
        return [
            self::STATUS_IN_PROGRESS => 'В работе',
            self::STATUS_COMPLETED => 'Завершен',
            self::STATUS_WRITTEN_OFF => 'Списан',
            self::STATUS_REPLACED => 'Заменен',
        ];
    }

    public function getStatus(): string
    {
        return self::statuses()[$this->status] ?? 'Неизвестный статус';
    }

    public function shaft()
    {
        return $this->hasOne(Shaft::class,'id','shaft_id');
    }

    public function operations()
    {
        return $this->hasMany(OperationLedgerEntry::class,'engraving_order_shaft_id','id')->orderBy('id');
    }

    public function resources()
    {
        return $this->hasMany(ShaftResource::class,'engraving_order_shaft_id','id')->orderBy('sequence');
    }
    
    public function phases()
    {
        return $this->hasMany(Phase::class,'engraving_order_shaft_id','id')->orderBy('sequence');
    }

    public function engravingOrder()
    {
        return $this->belongsTo(EngravingOrder::class, 'engraving_order_id');
    }

    public function getShaftOrderNumber(): int
    {
        return self::where('engraving_order_id', $this->engraving_order_id)
            ->whereNotNull('shaft_id')
            ->where('id', '<=', $this->id)
            ->count();
    }

    public function consumpShaft()
    {
        return $this->hasOne(ShaftConsumption::class,'engraving_order_shaft_id','id');
    }

    public function activeStage()
    {
        return $this->hasOneThrough(
            PhaseStage::class, 
            Phase::class, 
            'engraving_order_shaft_id', 
            'phase_id'
        )->where('phase_stages.status', 'in_progress')
         ->where('phases.status', 'in_progress'); // Добавляем фильтр для Phase
    }

    public function getSeparationAttribute()
    {
        return self::where('engraving_order_id', $this->engraving_order_id)
            ->where('sequence_number', '<=', $this->sequence_number)
            ->get()
            ->reduce(function ($separation, $engravingOrderShaft) {
                if (!is_null($engravingOrderShaft->color)) { 
                    $separation++;
                }

                $separation += max(
                    substr_count($engravingOrderShaft->lineature ?? '', '/'),
                    substr_count($engravingOrderShaft->corner ?? '', '/'),
                    substr_count($engravingOrderShaft->cutter ?? '', '/')
                );

                return $separation;
            }, 0);
        
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($engravingOrderShaft) {
            if ($engravingOrderShaft->isDirty('shaft_id') && $engravingOrderShaft->shaft_id !== null) {
                Shaft::where('id', $engravingOrderShaft->getOriginal('shaft_id'))->update(['state' => 'free']);
                Shaft::where('id', $engravingOrderShaft->shaft_id)->update(['state' => 'engraving']);
            }
        });

        static::updated(function ($engravingOrderShaft) {
            if ($engravingOrderShaft->isDirty('status') && $engravingOrderShaft->status == 'completed' && $engravingOrderShaft->engravingOrder->engravingOrderShaft->where('status','completed')->count() == $engravingOrderShaft->engravingOrder->quantity_shaft) {
                $engravingOrderShaft->engravingOrder->update(['condition_id' => 9]);
            }
        });
    }

    public function calculateFinalDiameter(): float
    {
        $shrinkage = $this->getShrinkage();
        
        $shaftEdge = ($this->sequence_number - 1) * 0.03;

        if (!$shrinkage) {
            return 0;
        }

        return round((($this->engravingOrder->print_step * $this->engravingOrder->fragments_in_circulation) / 3.1416 * $shrinkage) + $shaftEdge,3);
    }

    public function getShrinkage(): float
    {
        $material = Material::where('id', $this->engravingOrder->profile?->primary_material_id)->first();

        $rules = ShrinkageRule::all();

        $specialShrinkage = $rules->first(fn($rule) =>
            ($rule->condition_type === 'product_code' && str_contains($order->gp_code, $rule->condition_value)) ||
            ($rule->condition_type === 'customer' && str_contains($macroOrder->customer, $rule->condition_value))
        );

        return $specialShrinkage->shrinkage ?? $material->shrinkage ?? 1.0013;
    }

    public function getXmlDataShaftAttribute()
    {
        return [
            'EskoParam' => [
                'Номер_x0020_ОКВиД' => $this->engravingOrder->okvid_number,   
                'НомерВалаЗаказа' => $this->sequence_number,  
                'ID_x0020_вала' => $this->shaft->code ?? 0,   
                'Pantone' => $this->color, 
                'Базовый' => $this->parameters['shortening_scale_length'],   
                'линиатура' => $this->lineature, 
                'угол' => $this->corner, 
                'резец' => $this->cutter, 
            ]
        ];
    }

    public function getXmlDataShaftAppAttribute()
    {
        return [
            'EskoParam' => [
                'Номер_x0020_ОКВиД' => $this->engravingOrder->okvid_number,   
                'НомерВалаЗаказа' => $this->sequence_number,  
                'ID_x0020_вала' => $this->shaft->code ?? 0, 
                'цвет' => $this->color, 
                'Pantone' => $this->color,  
                'линиатура' => $this->lineature, 
                'угол' => $this->corner, 
                'резец' => $this->cutter, 
                'свободен' => ($shaft?->state ?? null) === 'free' ? 1 : 0,
                'Примечание' => $this->note,
            ]
        ];
    }
}
