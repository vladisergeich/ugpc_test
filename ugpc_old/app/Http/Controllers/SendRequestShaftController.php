<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ugpc\ShaftRequest;
use App\Models\Ugpc\ShaftRequestComposition;
use App\Models\BDGP\Designer;
use App\Models\BDGP\Vendor;
use App\Models\BDGP\Shaft;
use App\Models\BDGP\ShaftOrder;
use App\Models\BDGP\EmailList;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\BDGP\Order;
use App\Models\BDGP\ShaftResource;
use Barryvdh\DomPDF\PDF;
use App\Mail\OrderShipped;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel as BaseExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShipmentExport;

class SendRequestShaftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    { 
        $week = new \DateTime();
        $week->modify('-7 day');

        $shaftrequest_topical = ShaftRequest::where(function ($query) use( $week) {
            $query->where('document_date', '>', $week)
                  ->orWhere('post',false);
        })->get();
    
        $shaftrequest = ShaftRequest::
        leftjoin('shaft_request_compositions', 'shaft_request_compositions.document_number', '=', 'shaft_requests.document_number')
        ->select('shaft_requests.*',DB::raw('COUNT(shaft_request_compositions.shaft_id) as shafts_count'),)
        ->groupBy('shaft_requests.document_number')
        ->orderBy('post','asc')
        ->orderBy('document_number','desc')
        ->get(); 

        return view('ugpc.sendrequestshaft.request_list', [
            'shaftrequests' => $shaftrequest,
            'shaftrequest_topical' => $shaftrequest_topical,
        ]);
    }

    public function requestCard($id)
    {
        $designers = Designer::where('position','планирование')->get();
        $vendors = Vendor::all();
        $shaftrequest = ShaftRequest::find($id);
        $shaftrequestcomposition = ShaftRequestComposition::with('shaft.lastOkvid','shaft.okvid')->where('document_number',$shaftrequest->document_number)->get();
        $shafts = Shaft::where('shaft_id','<>',0)->with('okvid','lastOkvid')->get();

        return view('ugpc.sendrequestshaft.request_card', [
            'shaftrequest' => $shaftrequest,
            'shaftrequestcomposition' => $shaftrequestcomposition,
            'designers' => $designers,
            'vendors' => $vendors,
            'shafts' => $shafts,
        ]);
    }

    public function getRequest(Request $request)
    {
        $data = ShaftRequest::where('document_number',$request->requestshaft)->first();

        return ($data);    
    }

    public function getRequestComposition(Request $request)
    {
        $data = ShaftRequestComposition::where('document_number',$request->requestshaft)->get();

        return ($data);    
    }

    public function addRequestOrder(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // Блокируем строки с таким же document_number, чтобы избежать гонки
            $maxLineNumber = DB::table('shaft_request_compositions')
                ->where('document_number', $request->requestshaft)
                ->lockForUpdate()
                ->max('line_number');
    
            $shaftParam = Shaft::where('shaft_id', $request->shaft['shaft_id'])->first();
    
            $newRequestOrder = new ShaftRequestComposition();
            $newRequestOrder->line_number = ($maxLineNumber ?? 0) + 1; // Если записей нет, начинаем с 1
            $newRequestOrder->document_number = $request->requestshaft;
            $newRequestOrder->okvid_number = $request->shaft['okvid']['okvid_number'] ?? null;
            $newRequestOrder->shaft_id = $request->shaft['shaft_id'];
            $newRequestOrder->ff = $shaftParam->ff;
            $newRequestOrder->shaft_format = $shaftParam->shaft_format;
            $newRequestOrder->save();
    
            return ShaftRequestComposition::with('shaft.lastOkvid', 'shaft.okvid')->find($newRequestOrder->id);
        });

    }

    public function confirmRequest(Request $request)
    {
        $request_compositions = ShaftRequestComposition::where('document_number',$request->document_number)->get();

        $emails = EmailList::where('sender',$request->sender)->where('recipient',$request->recipient)->where('confirm',true)->get();

        $data = array('body'=>'Создано новое перемещение из'.' '.$request->sender.' '.'в'.' '.$request->recipient.'.'.' '.'Номер заявки'.' '.$request->document_number.' '.'Кол-во валов:'.' '.$request_compositions->count()); 
        foreach ($emails as $email) {
            Mail::send('ugpc/mail_request_confirm',$data,function($message) use ($email){
                $message->to($email->emails)->subject("Заявка на перемещение валов глубокой печати");
                $message->from('d.portal@danaflex.ru','UGPC-Portal');
            });
        }

        return ('ok');

    }

    public function updateRequest(Request $request)
    {
        $request_shaft = ShaftRequest::where('document_number',$request->document_number)->first();
        $request_shaft->update = true;
        $request_shaft->post = false;
        $request_shaft->save();

        return $request_shaft;
    }

    public function postShaftRequest(Request $request)
    {
        $request_shaft = ShaftRequest::where('document_number',$request->requestshaft)->first();
        
        $email = EmailList::where('sender',$request_shaft->sender)->where('recipient',$request_shaft->recipient)->where('confirm',false)->first();
        $emails = EmailList::where('sender',$request_shaft->sender)->where('recipient',$request_shaft->recipient)->where('confirm',false)->get();

        
        $shaftrequest = ShaftRequest::where('document_number',$request->requestshaft)->first();


        Mail::to($email->emails)->cc($emails->unique('emails')->pluck('emails'))->send(new OrderShipped($shaftrequest,$request->poddon_count));
        
        $request_compositions = ShaftRequestComposition::where('document_number',$request->requestshaft)->get();

        foreach ($request_compositions as $request_composition) {
            $shaftorder = ShaftOrder::where('shaft_id',$request_composition->shaft_id)->orderBy('id','desc')->first();
            if ($shaftorder != null) {
                $shaftorder->order_status = $request_shaft->recipient;   
                $shaftorder->warehouse_place = null;   
                $shaftorder->save();  
            }

            $shaft = Shaft::where('shaft_id',$request_composition->shaft_id)->first();
            $shaft->warehouse = $request_shaft->recipient;  
            $shaft->warehouse_place = null;
            $shaft->save();  

            $request_composition->document_date = $request_shaft->shipping_date;
            $request_composition->save();
        }

        $request_shaft->document_status = 'post';
        $request_shaft->post = 1;
        $request_shaft->save();

        return ($request);

    }

    public function submitRequest(Request $request) 
    {

        $request_id = ShaftRequest::find($request->request_id['id']);
        $request_id->update($request->request_id);
           
        return($request_id);
    }

    public function submitRequestComposition(Request $request) 
    {

        $composition = ShaftRequestComposition::find($request->composition['id']);
        $composition->update($request->composition);
           
        return($composition);
    }

    public function deleteRequestOrder(Request $request)
    {

        $data = ShaftRequestComposition::where('id',$request->order)->delete();

        $data_comps = ShaftRequestComposition::with('shaft.lastOkvid','shaft.okvid')->where('document_number',$request->document)->get();

        $number = 1;
        foreach ($data_comps as $data_comp) {
            $data_comp->line_number = $number;
            $data_comp->save();

            $number=$number+1;
        }

        

        return ($data_comps);
    }

    public function sendPdf(Request $request)
    {    
        $shafts = array();
        
        $shaftrequest = ShaftRequest::find($request->shaftrequest);
        $shaftrequestcomp = ShaftRequestComposition::where('document_number',$shaftrequest->document_number)->get();
        $sender = Vendor::where('vendor_name',$shaftrequest->sender)->first();
        $recipient = Vendor::where('vendor_name',$shaftrequest->recipient)->first();

        $poddon_count = 0;

        foreach ($shaftrequestcomp as $comp) {

            $sender_array = ['Упак-Рото','Яношка-Павловск','Клишировка Юньчен Новгород'];
            $okvid = Order::where('okvid_number',$comp->okvid_number)->first();
            $okvids = ShaftOrder::where('shaft_id',$comp->shaft_id)->get();
            if (count($okvids) > 1) {
                if (!in_array($shaftrequest->sender, $sender_array)) {
                    if (($shaftrequest->sender != 'Данафлекс-НАНО') && ($shaftrequest->recipient != 'Данафлекс-Алабуга')) {
                        $last_okvid = $okvids[count($okvids)-2];
                    } else {
                        $last_okvid = '';
                    }
                } else {
                    $last_okvid = '';
                }
            } else {
                if (!in_array($shaftrequest->sender, $sender_array)) {
                    if (($shaftrequest->sender != 'Данафлекс-НАНО') && ($shaftrequest->recipient != 'Данафлекс-Алабуга')) {
                        $last_okvid = $okvid;
                    } else {
                        $last_okvid = '';
                    }
                } else {
                    $last_okvid = '';
                }
            }
            $shaft_order = ShaftOrder::where('shaft_id',$comp->shaft_id)->get()->last();

            array_push($shafts,[$comp,$shaft_order,$okvid,$last_okvid]);
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('ugpc.pdf.sendrequest', compact('shaftrequest','shaftrequestcomp','sender','recipient','shafts','poddon_count'))->setPaper('a4', 'p');
        
        return response($pdf->output())
              ->header('Content-Type', 'application/pdf');         
    }


    public function addRequest()
    {

        $data = ShaftRequest::all()->count();

        $today = new \DateTime();

        $newRequest = New ShaftRequest();
        $newRequest->document_number = $data+1;
        $newRequest->document_date = $today;
        $newRequest->document_status = 'open';
        $newRequest->save();

        return redirect()->route('ugpc.sendrequestshaft.card',$newRequest);


    }

    public function updateShaft(Request $request)
    {
        $shaft_dest = ShaftRequestComposition::find($request->shaft['id']);
        $shaft_dest->update($request->shaft);
           

        return ($request);    
    }


    



    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
