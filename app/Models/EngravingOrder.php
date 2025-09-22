<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EngravingOrderShaft,TransferShaft,Order, MountingParameter, Designer, MacroOrder, Profile, EngravingOrderStatus, ShaftConsumption, EngravingOrderCondition, Vendor, LayoutConstructor, MovementOrder};
use Illuminate\Database\Eloquent\Relations\MorphOne;

class EngravingOrder extends Model
{
    use HasFactory;
    protected $guarded = ['id','sequence_number'];

    protected $fillable = [
        'macro_order_id', 'sequence_number', 'order_id', 'profile_id', 'status_id',
        'condition_id', 'repro_id', 'engraver_id', 'designer_id', 'gradation_increase',
        'order_approval_date', 'cutting_line_color', 'printing_date', 'engraving_request_user_id',
        'engraving_request_date', 'quantity_shaft', 'repeat_engraving_order_id', 'comments',
        'type_work_parameters', 'technological_elements', 'additional_options', 'parameters',
        'mounting_parameter_id', 'format', 'material_width', 'stream_width', 'print_step',
        'streams_quantity', 'fragments_in_circulation', 'sleeve_length', 'engraving_width',
        'previous_engraving_order_id', 'universal_shaft'
    ];

    protected $casts = [
        'comments' => 'array',
        'type_work_parameters' => 'array',
        'technological_elements' => 'array',
        'additional_options' => 'array',
        'parameters' => 'array',
    ];
    
    protected $appends = ['okvid_number'];

    protected $hidden = [
        // другие атрибуты
    ];
    
    protected static function booted()
    {
        static::created(fn($engravingOrder) => $engravingOrder->createMovementOrder());
    }

    public function createMovementOrder()
    {
        MovementOrder::updateOrCreate(
            ['related_id' => $this->id,'related_type' => EngravingOrder::class]
        );

    }

    public function transferShaftEngraving()
    {
        return $this->hasOne(TransferShaft::class,'engraving_order_id')->where('type','engraving')->orderBy('sequence');
    }

    public function transferShaftPrinting()
    {
        return $this->hasOne(TransferShaft::class,'engraving_order_id')->where('type','printing')->orderBy('sequence');
    }

    public function getOkvidNumberAttribute()
    {
        return sprintf('%s-%02d', $this->macro_order_id, $this->sequence_number);
        
    }

    public function engravingOrderShaft()
    {
        return $this->hasMany(EngravingOrderShaft::class,'engraving_order_id')->orderBy('sequence_number');
    }

    public function layoutStreams()
    {
        return $this->hasMany(LayoutConstructor::class,'engraving_order_id','id')->orderBy('stream_number');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function requestUser()
    {
        return $this->hasOne(Designer::class,'id','engraving_request_user_id');
    }

    public function designer()
    {
        return $this->hasOne(Designer::class,'id','designer_id');
    }

    public function mountingParameter()
    {
        return $this->hasOne(MountingParameter::class,'id','mounting_parameter_id');
    }

    public function movementOrder(): MorphOne
    {
        return $this->morphOne(MovementOrder::class, 'related');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class,'id','profile_id');
    }

    public function status()
    {
        return $this->hasOne(EngravingOrderStatus::class,'id','status_id');
    }

    public function condition()
    {
        return $this->hasOne(EngravingOrderCondition::class,'id','condition_id');
    }

    public function engraver()
    {
        return $this->hasOne(Vendor::class,'id','engraver_id');
    }

    public function repro()
    {
        return $this->hasOne(Vendor::class,'id','repro_id');
    }

    public function repeatEngravingOrder()
    {
        return $this->hasOne(EngravingOrder::class,'id','repeat_engraving_order_id');
    }

    public function macroOrder()
    {
        return $this->hasOne(MacroOrder::class,'id','macro_order_id');
    }

    public function getShrinkageCoefficient(): float
    {
        $material = Material::where('name', $this->profile->primary->shrinkage)->first();

        $rules = ShrinkageRule::all();

        $specialShrinkage = $rules->first(fn($rule) =>
            ($rule->condition_type === 'product_code' && str_contains($this->order->gp_code, $rule->condition_value)) ||
            ($rule->condition_type === 'customer' && str_contains($this->macroOrder->customer, $rule->condition_value))
        );

        // Возвращаем коэффициент (приоритет у особых правил)
        return $specialShrinkage->shrinkage ?? $material->shrinkage ?? null;
    }

    public function getPdfNameAttribute()
    {
        if ($this->parameters['updated_application']) {
            $clarified = -1;
        }else {
            $clarified = 0;
        }

        return  '5'.'_'.'5'.'_'.'2'.'_'.$this->okvid_number.'_'.$this->order_approval_date.'_'.'0'.'_'.$this->designer->reduction.'_'.$this->order->order_number.'_'.$this->fragment_in_circulation.'_'.$clarified.'_'.$this->repro_id.'_'.$this->engraving_id.'.pdf';
    }
}
