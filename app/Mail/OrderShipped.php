<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ugpc\ShaftRequest;
use App\Models\Ugpc\ShaftRequestComposition;
use Barryvdh\DomPDF\PDF;
use App\Models\BDGP\Designer;
use App\Models\BDGP\Vendor;
use App\Models\BDGP\Order;
use App\Models\BDGP\Shaft;
use Illuminate\Support\Facades\Storage;
use App\Models\BDGP\ShaftOrder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShipmentExport;
use Maatwebsite\Excel\Excel as BaseExcel;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    protected $shaftrequest;
    protected $poddon_count;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shaftrequest,$poddon_count)
    {
        $this->shaftrequest = $shaftrequest;
        $this->poddon_count = $poddon_count;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //$user = Auth::user();

        $shafts = array();
        $shaftrequest = ShaftRequest::where('document_number',$this->shaftrequest->document_number)->first();
        $shaftrequestcomp = ShaftRequestComposition::where('document_number',$shaftrequest->document_number)->get();
        $shafts_count = ShaftRequestComposition::where('document_number',$shaftrequest->document_number)->count();
        $sender = Vendor::where('vendor_name',$shaftrequest->sender)->first();
        $recipient = Vendor::where('vendor_name',$shaftrequest->recipient)->first();

        
        if (($shaftrequest->sender == 'БиекТау' || $shaftrequest->sender == 'ИП Калмыкова') || ($shaftrequest->recipient == 'БиекТау' || $shaftrequest->recipient == 'ИП Калмыкова')) {
            
            $shaftrequestcomp_excel = ShaftRequestComposition::with('shaft.lastOkvid.order','shaft.okvid')
                ->where('document_number', $this->shaftrequest->document_number)
                ->join('orders', 'orders.okvid_number', '=', 'shaft_request_compositions.okvid_number')
                ->join('macro_orders', 'macro_orders.macro_id', '=', 'orders.upak_order')
                ->selectRaw('macro_orders.macro_id,shaft_request_compositions.*')
                ->get();


            $macros = [];

            foreach ($shaftrequestcomp_excel as $shaft) {
                if (($shaftrequest->sender == 'БиекТау' || $shaftrequest->sender == 'ИП Калмыкова') || ($shaftrequest->recipient == 'БиекТау' || $shaftrequest->recipient == 'ИП Калмыкова')) {
                    if ($shaft->destination == 'Гравировка') {                  
                        if (array_key_exists($shaft->shaft->lastOkvid->order->upak_order, $macros)) {
                            $macros[$shaft->shaft->lastOkvid->order->upak_order] = $macros[$shaft->shaft->lastOkvid->order->upak_order].','.$shaft->shaft_id;
                        }else {
                            $macros[$shaft->shaft->lastOkvid->order->upak_order] = $shaft->shaft_id;
                        }
                    }else {
                        if (array_key_exists($shaft->shaft->okvid->order->upak_order, $macros)) {
                            $macros[$shaft->shaft->okvid->order->upak_order] = $macros[$shaft->shaft->okvid->order->upak_order].','.$shaft->shaft_id;
                        }else {
                            $macros[$shaft->shaft->okvid->order->upak_order] = $shaft->shaft_id;
                        }
                    }
                } else {
                    $macros[$shaft->macro_id] = $shaft->shaft_ids;
                }
            }

            $excel = Excel::raw(new ShipmentExport($macros,$shaftrequest), BaseExcel::XLSX);

        }
        
        $poddon_count = $this->poddon_count;

        $sender_array = ['Упак-Рото', 'Яношка-Павловск', 'Клишировка Юньчен Новгород'];

        $shaft_orders = ShaftOrder::whereIn('shaft_id', $shaftrequestcomp->pluck('shaft_id'))->get();
        $okvids = Order::whereIn('okvid_number', $shaftrequestcomp->pluck('okvid_number'))->get();

        foreach ($shaftrequestcomp as $comp) {
            $last_okvid = null;

            $related_shaft_orders = $shaft_orders->where('shaft_id', $comp->shaft_id);
            if ($related_shaft_orders->count() > 1 && !in_array($shaftrequest->sender, $sender_array)) {
                if (!($shaftrequest->sender == 'Данафлекс-НАНО' && $shaftrequest->recipient == 'Данафлекс-Алабуга')) {
                    $last_okvid = $related_shaft_orders->reverse()->skip(1)->first();
                }
            }

            $okvid = $okvids->where('okvid_number', $comp->okvid_number)->first();
            $shaft_order = $related_shaft_orders->last();

            $shafts[] = [$comp, $shaft_order, $okvid ?? '', $last_okvid ?? ''];
        }

        

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('ugpc.pdf.sendrequest', compact('shaftrequest','shaftrequestcomp','sender','recipient','shafts','poddon_count'))->setPaper('a4', 'p');


        $body = 'Учтено новое перемещение из'.' '.$shaftrequest->sender.' '.'в'.' '.$shaftrequest->recipient.'.'.' '.'Номер заявки'.' '.$shaftrequest->document_number.' '.'Кол-во валов:'.' '.$shafts_count.' '.'Кол-во поддонов:'.' '.$poddon_count.', '.'Масса:'.' '.($shaftrequestcomp->count()*160); 
        
        if ($shaftrequest->update == false) {
            if (($shaftrequest->sender == 'БиекТау' || $shaftrequest->sender == 'ИП Калмыкова') || ($shaftrequest->recipient == 'БиекТау' || $shaftrequest->recipient == 'ИП Калмыкова')) {
                return $this->markdown('emails.orders.shipped')
                        ->subject("Перемещение из"." ".$shaftrequest->sender." "."в"." ".$shaftrequest->recipient." ".".Номер перемещения"." ".$shaftrequest->document_number)
                        ->attachData($excel, 'upak_list.xlsx')
                        ->with([
                            'body' => $body,
                        ]);
            }else {
          
                return $this->markdown('emails.orders.shipped')
                        ->subject("Перемещение из"." ".$shaftrequest->sender." "."в"." ".$shaftrequest->recipient." ".".Номер перемещения"." ".$shaftrequest->document_number)
                        ->attachData($pdf->output(), 'upak_list.pdf')
                        ->with([
                            'body' => $body,
                        ]);
            }
        } else {

            if (($shaftrequest->sender == 'БиекТау' || $shaftrequest->sender == 'ИП Калмыкова') || ($shaftrequest->recipient == 'БиекТау' || $shaftrequest->recipient == 'ИП Калмыкова')) {
                return $this->markdown('emails.orders.shipped')
                    ->subject("Перемещение из"." ".$shaftrequest->sender." "."в"." ".$shaftrequest->recipient." ".".Номер перемещения"." ".$shaftrequest->document_number." "."(UPDATE)")
                    ->attachData($excel, 'upak_list.xlsx')
                    ->with([
                        'body' => $body,
                    ]);
            } else {

            
                return $this->markdown('emails.orders.shipped')
                        ->subject("Перемещение из"." ".$shaftrequest->sender." "."в"." ".$shaftrequest->recipient." ".".Номер перемещения"." ".$shaftrequest->document_number." "."(UPDATE)")
                        ->attachData($pdf->output(), 'upak_list.pdf')
                        ->with([
                            'body' => $body,
                        ]);
            }
        }
    }
}
