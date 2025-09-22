<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BDGP\MacroOrder;
use App\Models\BDGP\Customer;
use App\Models\BDGP\Order;
use App\Models\BDGP\ShaftOrder;
use App\Models\BDGP\MountingParameter;
use App\Models\Ugpc\MovementOrder;

class OkvidCardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
        $mountig_parameters = MountingParameter::all();
        $macroorder = MacroOrder::all()->last();
        
        return view('ugpc.okvidCard.index', [
            'mountig_parameters' => $mountig_parameters,
            'customers' => $customers,
            'macroorder' => $macroorder,
        ]);
    }

    public function getOkvid(Request $request) 
    {
        $data = MacroOrder::where('macro_id',$request->okvid)->first();
        

        return ($data);      
    }

    public function addOkvid(Request $request) 
    {
        $okvidLast = MacroOrder::latest('macro_id')->first();
        $okvidNew = New MacroOrder();
        $okvidNew->macro_id = $okvidLast->macro_id+1; 
        $okvidNew->new_id_macro = $okvidLast->macro_id+1;
        $okvidNew->save();
        
        $orderNew = New Order();
        $orderNew->okvid_number = $okvidNew->macro_id*100;
        $orderNew->upak_order = $okvidNew->macro_id;
        $orderNew->order_accepted_date = date('Y-m-d');
        $orderNew->order_status = 'Новый';
        $orderNew->gradation_increase = 0.03;
        $orderNew->traffic_lights_left = true;
        $orderNew->drive_label_left = true;
        $orderNew->drive_label_left = true;
        $orderNew->cross_left = true;
        $orderNew->cross_right = true;
        $orderNew->cutting_line_right = true;
        $orderNew->electronic_file = true;
        $orderNew->new_job = true;
        $orderNew->prepress = true;
        $orderNew->test_print = true;
        $orderNew->sleeve_lenght = 1380;
        $orderNew->width_engraving = 1340;
        $orderNew->cut_line_color = '70%black';
        $orderNew->tag_rsp_quantity = 8;
        $orderNew->tag_l_quantity = 8;
        $orderNew->gap_a_quantity = 0;
        $orderNew->gap_b_quantity = 0;
        $orderNew->field_c_quantity = 5;
        $orderNew->trim_release = 2;
        $orderNew->prod_order = '';
        $orderNew->imprinted = false;
        $orderNew->engraved = false;

        $new_movement_order = new MovementOrder();
        $new_movement_order->okvid_number = $okvidNew->macro_id*100;
        $new_movement_order->save();

        $orderNew->save();
        return $okvidNew;
    }

    public function updateOkvid(Request $request)
    {
        $okvid = MacroOrder::find($request->okvid['id']);
        $okvid->update($request->okvid);
        
        return($request);
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
