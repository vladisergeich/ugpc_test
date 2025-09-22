<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{Item,Order};

class OrderItem extends Model
{
    use HasFactory;
    protected $guarded = ['id','order_id','item_id'];

    protected $fillable = [
        'order_id','item_id','streams_quantity'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getXmlDataAppAttribute()
    {
        return [
            "Запрос_экспортXML" => [
                'ОбщееОписание' => $this->order->engravingOrder->macroOrder->note,
                'Номер_x0020_ОКВиД' => $this->order->engravingOrder->okvid_number,
                'Списан' => false,
                'серия_x0020_краски' => $this->order->engravingOrder->profile->paint_series,
                'Заявка_x0020_на_x0020_монтаж' => $this->order->engravingOrder->engraving_request_date,
                //'Число_x0020_стадий' => 
                'фрагментов_x0020_в_x0020_обороте' => $this->order->engravingOrder->fragments_in_circulation,
                'Поле_x0020_С' => 5,
                'зазор_x0020_B' => 0,
                'зазор_x0020_А' => 0,
                'метки_x0020_L' => 8,
                'Метки_x0020_RSP' => 8,
                'цвет_x0020_линии_x0020_реза' => $this->order->engravingOrder->cutting_line_color,
                'Варианты_x0020_намотки' => $this->winding_option,
                'шаг_x0020_фотометки' => $this->print_step,
                'кол-во_x0020_ручьев' => $this->streams_quantity,
                'метки_x0020_приводки_x0020_слева' => $this->order->engravingOrder->technological_elements['drive_label_left'],
                '_x0022_светофор_x0022__x0020_слева' => $this->order->engravingOrder->technological_elements['traffic_lights_left'],
                'Ширина_x0020_ручья' => $this->stream_width,
                'Метка_x0020_CS' => $this->order->engravingOrder->additional_options['label_coldseal'],
                'линия_x0020_для_x0020_резки_x0020_слева' => $this->order->engravingOrder->technological_elements['cutting_line_left'],
                'линия_x0020_для_x0020_резки_x0020_справа' => $this->order->engravingOrder->technological_elements['cutting_line_right'],
                'кресты_x0020_слева' => $this->order->engravingOrder->technological_elements['cross_left'],
                'кресты_x0020_справа' => $this->order->engravingOrder->technological_elements['cross_right'],
                '_x0022_светофор_x0022__x0020_справа' => $this->order->engravingOrder->technological_elements['traffic_lights_right'],
                'вторичка' => $this->order->engravingOrder->profile->secondary_material_id,
                'метки_x0020_приводки_x0020_справа' => $this->order->engravingOrder->technological_elements['drive_label_right'],
                'первичка' => $this->order->engravingOrder->profile->primary_material_id,
                'Expr1029' => 0,
                'Электронный_x0020_файл' => true,
                'Промобразец' => false,
                'Состояние' => $this->order->engravingOrder->condition->name ?? null,
                'Папка' => $this->order_number,
                'Expr1034' => 1,
                'Код_x0020_ГП' => $this->item->reduction,
                'Формат' => $this->order->engravingOrder->format,
                'Краткое_x0020_название' => $this->order->engravingOrder->profile->code,
                'Expr1038' => 7,
                'Увеличение_x0020_по_x0020_градациям' => $this->order->engravingOrder->gradation_increase,
                'Количество_x0020_цилиндров' => $this->order->engravingOrder->quantity_shaft,
                'Печать' => $this->order->engravingOrder->profile->print_type,
                'Допечатная_x0020_подготовка' => $this->order->engravingOrder->type_work_parameters['installation'],
                'Expr1043' => 0,
                'Пробопечать' => $this->order->engravingOrder->type_work_parameters['test_print'],
                'Цветопроба' => $this->order->engravingOrder->type_work_parameters['color_proof'],
                'Перегравировка_x0020_заказа' => $this->order->engravingOrder->type_work_parameters['reengraving'],
                'Гравировка_x0020_с_x0020_изменением' => $this->order->engravingOrder->type_work_parameters['engraving_with_change'],
                'Изменение_x0020_диаметра' => false,
                'Перехромирование' => false,
                'Новая_x0020_работа' => $this->order->engravingOrder->type_work_parameters['new_job'],
                'ВнешКоментарий1' => $this->order->engravingOrder->comments['internal_comment'],
                'Ширина_x0020_запеч_x0020_мат-ла' => $this->order->engravingOrder->material_width,
                'ICC-аналог_x0020_поставщика' => $this->order->engravingOrder->profile->supplier_analog_icc,
                'Код' => $this->order->engravingOrder->repro_id,
                'МенеджерОЗС' => $this->order->engravingOrder->support_manager_name,   
                'название' => $this->item->description,
                'сапкод' => $this->item->sap_code,
                'Статус_x0020_заказа' => $this->order->engravingOrder->status_id,
                'Изготовление_x0020_болванки' => 0,
                'Заявку_x0020_на_x0020_монтаж_x0020__x0020_заполнил' => $this->order->engravingOrder->requestUser->name ?? '', 
                'Заказчик' => $this->order->engravingOrder->macroOrder->sap_code,
                'Категория' => $this->item->category,
                'Бренд' => $this->item->brand,
                'Папки_x0020_нет' => $this->order->engravingOrder->parameters['without_unloading_application'],
                'Репро' => $this->order->engravingOrder->repro_id,
                'Гравировка' => $this->order->engravingOrder->engraver_id,
                'Тест_x0020__x2116_' => $this->order->engravingOrder->repeatEngravingOrder->okvid_number ?? '',
                'Длина_гильзы' => $this->order->engravingOrder->sleeve_length ?? 0,
            ]
        ];
    }
}
