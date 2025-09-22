<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{EngravingOrder, Item};

class LayoutConstructor extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = [
        'engraving_order_id','item_id','stream_number' 
    ];

    public function engravingOrder()
    {
        return $this->belongsTo(EngravingOrder::class, 'engraving_order_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


    public function getXmlDataStreamsAttribute()
    {
        return [
            "EskoVerst" => [
                'Номер_x0020_ОКВиД' => $this->engravingOrder->okvid_number,   
                '_x2116_ручья' => $this->stream_number,
                'Код_x0020_ГП' => $this->reduction,
                'Папка' => $this->engravingOrder->order->order_number,
                'Заявка_x0020_на_x0020_монтаж' => $this->engravingOrder->engraving_request_date,
                'СтатусЗаказа' => $this->engravingOrder?->status?->name,     
                'Состояние' => $this->engravingOrder?->condition?->name,    
                'МенеджерОЗС' => $this->engravingOrder->order->support_manager_name,       
                'код_x0020_Nav' => $this->engravingOrder->designer_id,    
                'Дизайнер' => $this->engravingOrder?->designer?->name,   
                'Формат' => $this->engravingOrder->format,
                'Код' => '1',
                'ОбщееОписание' => $this->item->category . '  ' . $this->item->brand, 
                'Краткое_x0020_название' => $this->engravingOrder?->profile?->code,
                'Ширина_x0020_запеч_x0020_мат' => $this->engravingOrder->material_width,
                'КрестыБлиды' => $this->engravingOrder?->mountingParameter?->code,
                'ESKO' => '0',
                'Заявку_x0020_на_x0020_монтаж_x0020__x0020_заполнил' => $this->engravingOrder?->requestUser?->name,
                'RotateFactor' => $this->engravingOrder->additional_options['rotate_factor'],   
                'BezReza' => $this->engravingOrder->additional_options['without_cutting'],   
                'AutoSmesheniye' => $this->engravingOrder->additional_options['automatic_stream'],   
                'ColdSeal' => $this->engravingOrder->additional_options['label_coldseal'],   
                'relsa' => $this->engravingOrder->additional_options['rail'],   
                'shetnechet' => $this->engravingOrder->number_stages == 'НЕЧЕТНОЕ' ? 0 : 1,
                'кол-во_x0020_ручьев' => $this->engravingOrder->streams_quantity,   
                'zazor' => $this->engravingOrder->additional_options['gap'],   
            ]
        ];
    }

}
