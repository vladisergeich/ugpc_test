<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ugpc\MachineCenter;
use App\Models\Ugpc\Stage;
use App\Models\Ugpc\EngravingOrder;
use App\Models\Ugpc\EngravingStage;
use App\Models\Ugpc\EngravingOrderStage;
use App\Models\Ugpc\OperationLedgerEntry;
use App\Models\Ugpc\WorkShiftCode;
use App\Models\Ugpc\Operation;
use App\Models\Ugpc\shaftState;
use App\Models\Ugpc\GlobalData;
use App\Models\Ugpc\ParameterOperation;
use App\Models\Ugpc\MovementOrder;
use App\Models\Ugpc\ParameterLedgerEntry;
use App\Models\BDGP\ShaftOrder;
use App\Models\BDGP\Shaft;
use App\Models\BDGP\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\Ugpc\PlanParameterLedgerEntry;
use App\Events\OperationEvent;
use Illuminate\Support\Facades\Mail;
use App\Events\WarehouseOrders;
use App\Http\Controllers\EngravingOrderController;


class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workShiftCode = WorkShiftCode::where('open',true)->first();
        $operator = Auth::user();

        $engravingHeads = GlobalData::where('variable_name','engraving_head')->get();
        $machines = MachineCenter::with('stages','endCutter','mainCutter','cascade')->whereIn('work_center',['CFM','K5','PP'])->get();
        return view('ugpc.machine',[
            'machines' => $machines,
            'work_shift_code' => $workShiftCode,
            'operator' => $operator,
            'engraving_heads' => $engravingHeads,
        ]);
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
    public function getWarehouseShafts(Request $request)
    {
        $machine = MachineCenter::with('stages')->find($request->machineId);
        $shaftState = shaftState::where('work_center',$machine->work_center)->pluck('engraving_order_id');

        $warehouseShafts= EngravingOrder::whereNotIn('id',$shaftState ?? [null])->with([
            'activeEngravingOrderStage.activeEngravingStage.planParams.parameter',
            'activeEngravingOrderStage.activeEngravingStage.stage.operations.params',
            'activeEngravingOrderStage.copperPlatingEditionStage.operation.thickness',
            'activeEngravingOrderStage.copperPlatingEditionStage.operation.factParameters.parameter',
            'activeEngravingOrderStage.grindingBase.operation.factParameters.parameter',
            'shaft',
            'design_order'
        ])
        ->whereHas('activeEngravingOrderStage.activeEngravingStage', function ($query) use ($machine) {
            $query->whereIn('stage_id', $machine->stages->pluck('id'));
        })
        ->get();

        return $warehouseShafts;
    }

    public function getOperationsMachine(Request $request)
    {
        $machine = MachineCenter::with('stages')->find($request->machineId);
        $workShiftCode = WorkShiftCode::where('open',true)->first();
        $operations = OperationLedgerEntry::with('engravingOrder.shaft','engravingOrder.design_order','operation','factDiameter','diameterDifference','headNumber')->where('machine_id',$request->machineId)->where('work_shift_code',$workShiftCode->code)->get();

        return $operations;
    }

    public function getSecondOperationsMachine(Request $request)
    {
        $machine = MachineCenter::with('stages')->find($request->machineId);
        $secondOperations = Operation::where('work_center',$machine->work_center)->where('type','secondary')->get();

        return $secondOperations;
    }

    public function getCurrentOperation(Request $request)
    {
        $currentOperation = OperationLedgerEntry::with('operation.params')->where('machine_id',$request->machineId)->where('end_time',null)->first();

        return $currentOperation;
    }

    public function getConsumpShaft(Request $request)
    {
        $shaftState = shaftState::where('machine_id',$request->machineId)->first();

        if ($shaftState) {
            $consumpShaft= EngravingOrder::with([
                'activeEngravingOrderStage.activeEngravingStage.planParams.parameter',
                'activeEngravingOrderStage.activeEngravingStage.stage.operations.params',
                'activeEngravingOrderStage.copperPlatingEditionStage.operation.thickness',
                'activeEngravingOrderStage.copperPlatingEditionStage.operation.factParameters.parameter',
                'activeEngravingOrderStage.grindingBase.operation.factParameters.parameter',
                'shaft',
                'design_order'
            ])->find($shaftState->engraving_order_id);
        } else {
            $consumpShaft = null;
        }

        return $consumpShaft;
    }

    public function deleteOperation(Request $request)
    {
        $workShiftCode = WorkShiftCode::where('open',true)->first();

        OperationLedgerEntry::find($request->currentOperation['id'])->delete();
        $currentOperation = OperationLedgerEntry::with('operation')->where('machine_id',$request->machineId)->where('end_time',null)->first();

        $operations = OperationLedgerEntry::with('engravingOrder.shaft','engravingOrder.design_order','operation','factDiameter')->where('machine_id',$request->machineId)->where('work_shift_code',$workShiftCode->code)->get();

        return ['operations' => $operations,'currentOperation' => $currentOperation];
    }

    public function closeOperation(Request $request)
    {
        $workShiftCode = WorkShiftCode::where('open',true)->first();

        ShaftState::where('machine_id', $request->machineId)->delete(); 

        $currentOperation = OperationLedgerEntry::updateOrCreate(
            ['id' => $request->currentOperation['id']],
            [   'end_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s'),
                'work_shift_code' => $workShiftCode->code ?? null,
            ],
        );

        if (in_array($currentOperation->operation_id,[8,9,10,11])) {
            $endCutter = GlobalData::where('variable_name','end_cutter')->where('variable_desc',$request->machineId)->first();
            $endCutter->variable_body += 1;
            $endCutter->save();

            $cascade = GlobalData::where('variable_name','cascade')->where('variable_desc',$request->machineId)->first();
            $cascade->variable_body += 1;
            $cascade->save();


            $copperPlatingEditionStage = EngravingOrder::with([
                'activeEngravingOrderStage.copperPlatingEditionStage.operation.thickness',
            ])
            ->find($request->currentOperation['engraving_order_id']);

            if (($currentOperation->operation_id == 8) || ($copperPlatingEditionStage->activeEngravingOrderStage->copperPlatingEditionStage && $copperPlatingEditionStage->activeEngravingOrderStage->copperPlatingEditionStage->operation->thickness->float_value > 150)) {
                $mainCutter = GlobalData::where('variable_name','main_cutter')->where('variable_desc',$request->machineId)->first();
                $mainCutter->variable_body += 1;
                $mainCutter->save();
            }
        }

        if (($currentOperation->operation_id == 14)) {
            $cascade = GlobalData::where('variable_name','cascade')->where('variable_desc',$request->machineId)->first();

            ParameterLedgerEntry::updateOrCreate(
                ['parameter_id' => 17, 'operation_ledger_entry_id' => $request->currentOperation['id']],
                [
                    'float_value' => $cascade->variable_body,
                ]
            );

            $cascade->variable_body = 0;
            $cascade->save();
        }

        if (($currentOperation->operation_id == 12)) {
            $mainCutter = GlobalData::where('variable_name','main_cutter')->where('variable_desc',$request->machineId)->first();

            ParameterLedgerEntry::updateOrCreate(
                ['parameter_id' => 18, 'operation_ledger_entry_id' => $request->currentOperation['id']],
                [
                    'float_value' => $mainCutter->variable_body,
                ]
            );

            $mainCutter->variable_body = 0;
            $mainCutter->save();
        }

        if (($currentOperation->operation_id == 13)) {
            $endCutter = GlobalData::where('variable_name','end_cutter')->where('variable_desc',$request->machineId)->first();

            ParameterLedgerEntry::updateOrCreate(
                ['parameter_id' => 19, 'operation_ledger_entry_id' => $request->currentOperation['id']],
                [
                    'float_value' => $endCutter->variable_body,
                ]
            );

            $endCutter->variable_body = 0;
            $endCutter->save();
        }
        
        foreach ($request->currentOperation['operation']['params'] as $param) {
            ParameterLedgerEntry::updateOrCreate(
                ['parameter_id' => $param['id'], 'operation_ledger_entry_id' => $request->currentOperation['id']],
                [
                    'float_value' => ($param['type'] === 'number') ? $param['value'] : null,
                    'string_value' => ($param['type'] === 'string') ? $param['value'] : null,
                ]
            );

            switch ($param['id']) {
                case 1:
                    if ($request['shaft']['active_engraving_order_stage']['active_engraving_stage']['stage_id'] == 3) {
                        $planDiameter = EngravingOrderStage::with([
                            'engravingStages' => function ($query) use ($request){
                                $query->whereIn('stage_id', [3,7]);
                                $query->where('id','>',$request['shaft']['active_engraving_order_stage']['active_engraving_stage']['id']);
                            },
                            'engravingStages.planDiameter',
                        ])
                        ->where('engraving_order_id',$request['shaft']['id'])
                        ->whereHas('engravingStages', function ($query) use ($request){
                            $query->whereIn('stage_id', [3,7]);
                            $query->where('id','>',$request['shaft']['active_engraving_order_stage']['active_engraving_stage']['id']);
                        })
                        ->first();

                        $copperPlating = round((($planDiameter->engravingStages->first()->planDiameter->float_value - $param['value']) * 1000 * 1.05) / 2);

                        if ($copperPlating > 150) {

                            $copperReserve = GlobalData::where('variable_name', 'copper_reserve')->where('variable_desc', '<=', $request['shaft']['format'])
                            ->get()->last();

                            $copperPlating += $copperReserve->variable_body;
                            
                        }

                        $planCopperPlating = EngravingOrderStage::with([
                            'engravingStages' => function ($query) use ($request){
                                $query->whereIn('stage_id', [6,12]);
                                $query->where('id','>',$request->shaft['active_engraving_order_stage']['active_engraving_stage']['id']);
                            },
                            'engravingStages.planCopperPlating',
                        ])->where('engraving_order_id',$request['shaft']['id'])
                        ->whereHas('engravingStages', function ($query) use ($request){
                            $query->whereIn('stage_id', [6,12]);
                            $query->where('id','>',$request->shaft['active_engraving_order_stage']['active_engraving_stage']['id']);
                        })
                        ->first();

                        if ($planCopperPlating) {
                            $planCopperPlatingEntry = $planCopperPlating->engravingStages->first()->planCopperPlating;
                        } 

                        $engravingOrder =EngravingOrder::with([
                            'activeEngravingOrderStage.activeEngravingStage.planParams.parameter',
                            'activeEngravingOrderStage.activeEngravingStage.stage.operations.params',
                            'shaft',
                            'design_order'
                        ])->find($currentOperation->engraving_order_id);

                        if (($copperPlating <= 350) && $planCopperPlatingEntry && (round((($param['value']-(round($engravingOrder->initial_size,3)-(round($engravingOrder->copper_thickness,3)*2/1050)))*1050/2),3) > 0)) {
                            $planCopperPlatingEntry->float_value = $copperPlating;
                            $planCopperPlatingEntry->save();
                        } else {
                            

                            if (round((($param['value']-(round($engravingOrder->initial_size,3)-(round($engravingOrder->copper_thickness,3)*2/1050)))*1050/2),3) <= 0) {
                                $engravingOrder->type_shaft = 'Сталь';
                                $engravingOrder->save();
                            } 

                            $EngravingOrderController = new EngravingOrderController();
                            $EngravingOrderController->createOrderStage($engravingOrder,$param['value'],$engravingOrder->final_size,$request['shaft']['active_engraving_order_stage']['stage_number']);
                        }
                    }
                    break;
                case 5:
                    
                    break;
            }
        }

        $engravingOrder =EngravingOrder::with([
            'activeEngravingOrderStage.activeEngravingStage.planParams.parameter',
            'activeEngravingOrderStage.activeEngravingStage.stage.operations.params',
            'shaft',
            'design_order'
        ])->find($currentOperation->engraving_order_id);
        
        if ($engravingStage = EngravingStage::find($currentOperation->engraving_stage_id)) {
            $engravingStage->update(['open' => false, 'post' => true]);
            if ($engravingNextStage = EngravingStage::where('engraving_order_stage_id',$engravingOrder->activeEngravingOrderStage->id)->where('stage_number', '>', $engravingStage->stage_number)->orderBy('engraving_order_stage_id')->orderBy('stage_number')->first()) {
                $engravingNextStage->update(['open' => true]);

                $shaft= EngravingOrder::with([
                    'activeEngravingOrderStage.activeEngravingStage.planParams.parameter',
                    'activeEngravingOrderStage.activeEngravingStage.stage.operations.params',
                    'shaft',
                    'design_order'
                ])
                ->find($engravingOrder->id);
                
                broadcast(new WarehouseOrders($shaft));
            } else {
                $engravingOrderStage =EngravingOrderStage::find($engravingOrder->activeEngravingOrderStage->id);
                $engravingOrderStage->status = 'завершен';
                $engravingOrderStage->save();

                if ($engravingNextOrderStage = EngravingOrderStage::where('engraving_order_id',$engravingOrder->id)->where('id', '>', $engravingOrder->activeEngravingOrderStage->id)->orderBy('stage_number')->first()) {
                    $engravingNextOrderStage->update(['status' => 'в работе']);
                    if ($engravingNextStage = EngravingStage::where('engraving_order_stage_id',$engravingNextOrderStage->id)->orderBy('stage_number')->first()) {
                        $engravingNextStage->update(['open' => true]);
                    }
                } else {
                    $engravingOrder->status = 'завершен';
                    $engravingOrder->save();

                    $endDiameterFact = EngravingOrder::with([
                        'lastEngravingOrderStage.grindingEditionStage.operation.factDiameter',
                    ])->find($engravingOrder->id);

                    $shaft = Shaft::find($engravingOrder->shaft_id);
                    $shaft->diameter = $endDiameterFact->lastEngravingOrderStage->grindingEditionStage->operation->factDiameter->float_value ?? null;
                    $shaft->save();

                    $movement_order = MovementOrder::where('okvid_number',$engravingOrder->design_order->okvid_number)->orderBy('id','desc')->first(); 
                    $movement_order->shaft_quantity_fact = $movement_order->shaft_quantity_fact+1;
                    $movement_order->save();

                    $allEngravingOrders = EngravingOrder::where('design_order_id',$engravingOrder->design_order_id)->where('status','<>','завершен')->get();
                    
                    if ($allEngravingOrders->count() == 0) {
                        $allEngravingOrders = EngravingOrder::with([
                            'lastEngravingOrderStage.grindingEditionStage.operation.factDiameter',
                            'lastEngravingOrderStage.grindingEditionStage.operation.factHardness',
                            'lastEngravingOrderStage.copperPlatingEditionStage.operation.thickness',
                            'shaft',
                            'design_order'
                        ])
                        ->where('design_order_id',$engravingOrder->design_order_id)
                        ->get();
                        
                        $emails = [
                            'v.arsentev@danaflex.ru',
                            'gravlab@danaflex.ru',
                            'i.zaidullina@danaflex.ru',
                            'K_UGPC_NANO@danaflex.ru',
                            'b.andryashin@danaflex.ru',
                        ];
                
                        foreach ($emails as $email) {
                            Mail::send('ugpc/mail_complete_engraving_order',['engraving_orders' => $allEngravingOrders],function($message) use ($email){
                                $message->to($email)->subject("Комплект валов готов");
                                $message->from('d.portal@danaflex.ru','UGPC-Portal');
                            });
                        }
                    
                    }
                }
            }
        }

        $operation = OperationLedgerEntry::with('machine','engravingOrder.shaft','engravingOrder.design_order','operation.params','engravingOrder.activeEngravingOrderStage.activeEngravingStage.planParams.parameter')->where('end_time',null)->get()->toArray();
        broadcast(new OperationEvent($operation));
        
        $currentOperation = OperationLedgerEntry::with('engravingOrder.shaft','engravingOrder.design_order','operation','factDiameter')->where('machine_id', $request->machineId)->whereNull('end_time')->first();

        $operations = OperationLedgerEntry::with('engravingOrder.shaft','engravingOrder.design_order','operation','factDiameter','diameterDifference','headNumber')->where('machine_id',$request->machineId)->where('work_shift_code',$workShiftCode->code)->get();

        return ['operations' => $operations,'currentOperation' => $currentOperation];
    }

    public function addPreCopperPlating(Request $request)
    {
        $workShiftCode = WorkShiftCode::where('open',true)->first();

        switch ($request->shaft['active_engraving_order_stage']['active_engraving_stage']['stage_id']) {
            case 3:
                EngravingStage::where('engraving_order_stage_id',$request->shaft['active_engraving_order_stage'])->where('stage_id',4)->delete();
                $EngravingStages = EngravingStage::where('engraving_order_stage_id',$request->shaft['active_engraving_order_stage']['id'])->where('id','>',$request->shaft['active_engraving_order_stage']['active_engraving_stage']['id'])->orderBy('stage_number')->get();

                foreach ($EngravingStages as $stage) {
                    $stage->stage_number += 2;
                    $stage->save();
                }

                $newStages = [2,5];

                $stageNumber = $request->shaft['active_engraving_order_stage']['active_engraving_stage']['stage_number'];
                foreach ($newStages as $newStage) {
                    $stageNumber += 1;
                    EngravingStage::create([
                        'engraving_order_stage_id' => $request->shaft['active_engraving_order_stage']['id'],
                        'stage_id' => $newStage,
                        'stage_number' => $stageNumber,
                    ]);
                }

                $currentOperation = OperationLedgerEntry::updateOrCreate(
                    ['id' => $request->currentOperation['id']],
                    ['end_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s')],
                );
                
                $this->closeOperation($request);

                break;
            case 7:
                EngravingStage::where('engraving_order_stage_id',$request->shaft['active_engraving_order_stage'])->where('stage_number', '>', $request->shaft['active_engraving_order_stage']['active_engraving_stage']['stage_number'])->delete();
                $EngravingOrderStages = EngravingOrderStage::where('engraving_order_id',$request->shaft['id'])->where('id','>',$request->shaft['active_engraving_order_stage']['id'])->orderBy('stage_number')->get();

                foreach ($EngravingOrderStages as $orderStage) {
                    $orderStage->stage_number += 1;
                    $orderStage->save();
                }

                $newEngravingOrderStage = EngravingOrderStage::create([
                    'engraving_order_id' => $request->shaft['id'],
                    'status' => 'в работе',
                    'stage_number' => $request->shaft['active_engraving_order_stage']['stage_number'] + 1,
                ]);

                $newStages = [2,5,7,9,8,10,11,13];

                $shaft = Shaft::find($request->shaft['shaft_id']);
                $okvid = Order::find($request->shaft['design_order_id']);
                $shaftorder = ShaftOrder::where('okvid_number',$okvid->okvid_number)->where('shaft_id', $shaft->shaft_id)->first();
                $stageNumber = 1;
                foreach ($newStages as $newStage) {
                    $newEngravingStage = EngravingStage::create([
                        'engraving_order_stage_id' => $newEngravingOrderStage->id,
                        'stage_id' => $newStage,
                        'stage_number' => $stageNumber,
                        'open' => $stageNumber == 1 ? true : false,
                    ]);

                    $parameters = [];

                    switch ($newStage) {
                        case 7:
                            $parameters = [
                                ['parameter_id' => 1, 'float_value' => $request->shaft['final_size']],
                                ['parameter_id' => 4, 'string_value' => '205...220'],
                                ['parameter_id' => 3, 'string_value' => $shaftorder->panton == 'ColdSeal' || $shaftorder->panton == 'Cold seal' || $shaftorder->color == 'Coldseal' || $shaftorder->color == 'Cold seal' ? '1,1...1,2' : '0,5...0,6'],
                            ];
                            break;
                        case 9:
                            $parameters = [
                                ['parameter_id' => 8, 'string_value' => $request->shaft['lineature']],
                                ['parameter_id' => 9, 'string_value' => $request->shaft['corner']],
                                ['parameter_id' => 10, 'string_value' => $request->shaft['cutter']],
                                ['parameter_id' => 11, 'float_value' => $request->shaft['separation_number']],
                            ];
                            break;
                        case 10:
                            $parameters = [
                                ['parameter_id' => 5, 'float_value' => 7],
                            ];
                            break;
                        case 13:
                            $parameters = [
                                ['parameter_id' => 7, 'string_value' => $request->shaft['color']],
                                ['parameter_id' => 3, 'string_value' => $shaftorder->panton == 'ColdSeal' || $shaftorder->panton == 'Cold seal' || $shaftorder->color == 'ColdSeal' || $shaftorder->color == 'Cold seal' ? '0,8...0,9' : '0,4...0,5'],
                            ];
                            break;
                    }
    
                    if ($parameters) {
                        foreach ($parameters as $param) {
                            PlanParameterLedgerEntry::updateOrCreate(
                                [
                                    'engraving_stage_id' => $newEngravingStage->id,
                                    'parameter_id' => $param['parameter_id'],
                                ],
                                [
                                    'float_value' => $param['float_value'] ?? null,
                                    'string_value' => $param['string_value'] ?? null,
                                ]
                            );
                        }
                    }
                    $stageNumber += 1;
                }

                $engravingOrderStage =EngravingOrderStage::find($request->shaft['active_engraving_order_stage']['id']);
                $engravingOrderStage->status = 'завершен';
                $engravingOrderStage->save();

                if ($engravingStage = EngravingStage::find($request->shaft['active_engraving_order_stage']['active_engraving_stage']['id'])) {
                    $engravingStage->update(['open' => false, 'post' => true]);
                }

                OperationLedgerEntry::find($request->currentOperation['id'])->delete();

                break;
        }

        ShaftState::where('machine_id', $request->machineId)->delete(); 

        $currentOperation = OperationLedgerEntry::with('operation')->where('machine_id', $request->machineId)->whereNull('end_time')->first();

        $operations = OperationLedgerEntry::with('engravingOrder.shaft','engravingOrder.design_order','operation','factDiameter')
            ->where('machine_id', $request->machineId)
            ->where('work_shift_code', $workShiftCode->code)
            ->get();

        return ['operations' => $operations,'currentOperation' => $currentOperation];
    }

    public function defectShaft(Request $request)
    {
        $user = Auth::user();
        $workShiftCode = WorkShiftCode::where('open',true)->first();
        $machine = MachineCenter::with('stages','workCenter')->find($request->machineId);

        shaftState::where('machine_id',$request->machineId)->delete(); 

        $currentOperation = OperationLedgerEntry::updateOrCreate(
            ['id' => $request->currentOperation['id']],
            ['end_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s')],
        );

        foreach ($request->currentOperation['operation']['params'] as $param) {
            ParameterLedgerEntry::updateOrCreate(
                ['parameter_id' => $param['id'], 'operation_ledger_entry_id' => $request->currentOperation['id']],
                [
                    'float_value' => ($param['type'] === 'number') ? $param['value'] : null,
                    'string_value' => ($param['type'] === 'string') ? $param['value'] : null,
                ]
            );
        }

        $engravingOrder =EngravingOrder::with([
            'activeEngravingOrderStage.activeEngravingStage.planParams.parameter',
            'activeEngravingOrderStage.activeEngravingStage.stage.operations.params',
            'shaft',
            'design_order'
        ])->find($currentOperation->engraving_order_id);

        $operation = Operation::where('description','Забраковка вала')->first();

        $newOperation = OperationLedgerEntry::create([
            'start_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s'),
            'end_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s'),
            'operation_id' => $operation->id,
            'engraving_order_id' => $engravingOrder->id ?? null,
            'engraving_stage_id' => $engravingOrder->activeEngravingOrderStage->activeEngravingStage->id ?? null,
            'posting_date' => $workShiftCode->date ?? null,
            'work_shift_code' => $workShiftCode->code ?? null,
            'work_center_id' => $machine->workCenter->id,
            'machine_id' => $request->machineId,
            'user_id' => $user->id ?? $request->operator['id'],
        ]);

        $engravingStage = EngravingStage::find($engravingOrder->activeEngravingOrderStage->activeEngravingStage->id);
        if ($request->engravingOrder) {
            $engravingStage->update($request->engravingOrder['active_engraving_order_stage']['active_engraving_stage']);
        } 

        $engravingStage->defect = true;
        $engravingStage->open = false;
        $engravingStage->save();

        $engravingOrder =EngravingOrder::find($engravingOrder->id);
        $engravingOrder->status = 'брак';
        $engravingOrder->save();

        $engravingOrderStage =EngravingOrderStage::find($engravingOrder->activeEngravingOrderStage->id);
        $engravingOrderStage->status = 'брак';
        $engravingOrderStage->save();

        $operations = OperationLedgerEntry::with('engravingOrder.shaft','engravingOrder.design_order','operation','factDiameter')->where('machine_id',$request->machineId)->where('work_shift_code',$workShiftCode->code)->get();

        return $operations;
    }

    public function consumpShaft(Request $request)
    {
        $machine = MachineCenter::with('stages')->find($request->machineId);

        if (shaftState::where('machine_id',$request->machineId)->doesntExist()) {
            $newConsumpShaft = shaftState::updateOrCreate(
                [   'machine_id' => $request->machineId],
                [   
                    'status' => 'consump',
                    'work_center' => $machine->work_center,
                    'engraving_order_id' => $request->engravingOrder['id'],
                ],
            );

            $warehouseShafts= EngravingOrder::where('id','<>',$request->engravingOrder['id'])->with([
                'activeEngravingOrderStage.activeEngravingStage.planParams.parameter',
                'activeEngravingOrderStage.activeEngravingStage.stage.operations.params',
                'activeEngravingOrderStage.copperPlatingEditionStage.operation.thickness',
                'activeEngravingOrderStage.copperPlatingEditionStage.operation.factParameters.parameter',
                'activeEngravingOrderStage.grindingBase.operation.factParameters.parameter',
                'shaft',
                'design_order'
            ])
            ->whereHas('activeEngravingOrderStage.activeEngravingStage', function ($query) use ($machine) {
                $query->whereIn('stage_id', $machine->stages->pluck('id'));
            })
            ->get();
    
            return $warehouseShafts;
        } else {
            return null;
        }

    }

    public function deleteConsumpShaft(Request $request)
    {
        $machine = MachineCenter::with('stages')->find($request->machineId);
        shaftState::where('engraving_order_id',$request->engravingOrder['id'])->delete(); 
        $warehouseShafts= EngravingOrder::with([
            'activeEngravingOrderStage.activeEngravingStage.planParams.parameter',
            'activeEngravingOrderStage.activeEngravingStage.stage.operations.params',
            'activeEngravingOrderStage.copperPlatingEditionStage.operation.thickness',
            'activeEngravingOrderStage.copperPlatingEditionStage.operation.factParameters.parameter',
            'activeEngravingOrderStage.grindingBase.operation.factParameters.parameter',
            'shaft',
            'design_order'
        ])
        ->whereHas('activeEngravingOrderStage.activeEngravingStage', function ($query) use ($machine) {
            $query->whereIn('stage_id', $machine->stages->pluck('id'));
        })
        ->get();

        return $warehouseShafts;
    }

    public function startOperation(Request $request)
    {
        $user = Auth::user();
        $workShiftCode = WorkShiftCode::where('open',true)->first();
        $machine = MachineCenter::with('stages','workCenter')->find($request->machineId);
        $operation = Operation::find($request->operationId);

        $newOperation = OperationLedgerEntry::create([
            'start_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s'),
            'operation_id' => $request->operationId,
            'engraving_order_id' => $request->shaft['id'] ?? null,
            'engraving_stage_id' => $request->shaft['active_engraving_order_stage']['active_engraving_stage']['id'] ?? null,
            'posting_date' => $workShiftCode->date ?? null,
            'work_shift_code' => $workShiftCode->code ?? null,
            'work_center_id' => $machine->workCenter->id,
            'machine_id' => $request->machineId,
            'user_id' => $user->id ?? $request->operator['id'],
        ]);

        $operation = OperationLedgerEntry::with('machine','engravingOrder.shaft','engravingOrder.design_order','operation.params','engravingOrder.activeEngravingOrderStage.activeEngravingStage.planParams.parameter')->where('end_time',null)->get()->toArray();
        broadcast(new OperationEvent($operation));

        $currentOperation = OperationLedgerEntry::with('operation.params')->find($newOperation->id);

        $operations = OperationLedgerEntry::with('engravingOrder.shaft','engravingOrder.design_order','operation','factDiameter','diameterDifference','headNumber')->where('machine_id',$request->machineId)->where('work_shift_code',$workShiftCode->code)->get();


        return ['operations' => $operations,'currentOperation' => $currentOperation];
    }

    public function getOpenOperationsWorkCenter(Request $request)
    {
        $machines = MachineCenter::where('work_center','EP')->pluck('id');

        $operations = OperationLedgerEntry::with('machine','engravingOrder.shaft','engravingOrder.design_order','operation.params','engravingOrder.activeEngravingOrderStage.activeEngravingStage.planParams.parameter')->whereIn('machine_id',$machines)->where('end_time',null)->get();

        return $operations;
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
