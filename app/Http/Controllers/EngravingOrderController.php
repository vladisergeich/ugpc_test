<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ugpc\MovementOrder;
use App\Models\Ugpc\EngravingOrder;
use App\Models\Ugpc\EngravingOrderStage;
use App\Models\Ugpc\PlanParameterLedgerEntry;
use App\Models\Ugpc\EngravingStage;
use App\Models\Ugpc\GlobalData;
use App\Models\Ugpc\WorkShiftCode;
use App\Models\Ugpc\OperationLedgerEntry;
use App\Models\BDGP\ShaftOrder;
use App\Models\BDGP\Shaft;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EngravingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $threeDay = new \DateTime();
        $threeDay->modify('-3 day');

        $engravingOrders = MovementOrder::where('engraver', 'Данафлекс-НАНО')->where('completion_date','>=',$threeDay)->with([
            'designOrder.engravingOrders.shaft.lastOkvid.order',
            'designOrder.engravingOrders' => function ($query) {
                $query->where('status','<>','завершен');
            },
            'designOrder.macroOrder'
        ])
        ->whereHas('designOrder.engravingOrders', function ($query)  {
            $query->where('status', '<>','завершен');
        })
        ->orderBy('completion_date')
        ->orderBy('priority')
        ->groupBy('okvid_number')
        ->get();
        

        $engravingOrdersEnded = MovementOrder::where('engraver', 'Данафлекс-НАНО')->with([
            'designOrder.engravingOrders.shaft',
            'designOrder.engravingOrders' => function ($query) {
                $query->where('status','=','завершен');
            },
            'designOrder.macroOrder'
        ])
        ->whereHas('designOrder.engravingOrders', function ($query)  {
            $query->where('status','=','завершен');
        })
        ->orderBy('completion_date','desc')
        ->orderBy('priority','desc')
        ->groupBy('okvid_number')
        ->get();

        return view('ugpc.engravingorder',[
            'engraving_orders' => $engravingOrders,
            'engraving_orders_ended' => $engravingOrdersEnded,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request)
    {
        $engravingOrder = EngravingOrder::with([
            'engravingOrderStages.engravingStages.planParams.parameter',
            'engravingOrderStages.engravingStages.stage',
            'engravingOrderStages.engravingStages.operation.factParameters.parameter',
            'shaft',
            'design_order'
        ])->find($request['id']);

        if (($engravingOrder->initial_size) && $engravingOrder->type_shaft) {

            $engravingOrder->update($request->all());
            $updateEngravingOrder = $this->createOrderStage($engravingOrder,$engravingOrder->initial_size - 0.144,$engravingOrder->final_size,1);
            return $updateEngravingOrder;
        } else {
            $engravingOrder->update($request->all());
            return $engravingOrder;
        }

    }

    public function updateEngravingTime(Request $request)
    {
        $engravingOrder = EngravingOrder::with([
            'engravingOrderStages.engravingStages.planParams.parameter',
            'engravingOrderStages.engravingStages.stage',
            'engravingOrderStages.engravingStages.operation.factParameters.parameter',
            'shaft',
            'design_order'
        ])->find($request['id']);

        $engravingOrder->update($request->all());
            
        return $engravingOrder;
    }

    public function alterationStage(Request $request)
    {
        $newEgravingOrderStage = EngravingOrderStage::create([
            'engraving_order_id' => $request->orderStage['engraving_order_id'],
            'stage_number' => $request->orderStage['stage_number'],
            'status' => 'в работе',
        ]);

        $engravingOrderStage =EngravingOrderStage::with('defectEngravingStage')->find($request->orderStage['id']);
        $engravingOrderStage->status = 'завершен';
        $engravingOrderStage->save();

        $defectOperation = OperationLedgerEntry::where('engraving_stage_id',$engravingOrderStage->defectEngravingStage->id)->where('operation_id','<>',68)->delete();

        $stageNumber = 1;
        foreach ($request['alterationStages'] as $stage) {
            if (array_key_exists('open',$stage)) {
                if ($stage['open']) {
                    $newEgravingStage = EngravingStage::create([
                        'engraving_order_stage_id' => $newEgravingOrderStage->id,
                        'stage_id' => $stage['id'],
                        'stage_number' => $stageNumber,
                        'open' => $stageNumber == 1 ? true : false,
                    ]);

                    //$firstEgravingOrderStage = EngravingOrderStage::where('engraving_order_id',$request->orderStage['engraving_order_id'])->where('stage_number',1)->first();

                    $newPlanParameters = EngravingStage::where('engraving_order_stage_id',$engravingOrderStage->id)->where('stage_id',$stage['id'])->with('planParams')->first();
        
                    if ($newPlanParameters) {
                        foreach ($newPlanParameters->planParams as $param) {
                            $newPlanParam = $param->replicate();
                            $newPlanParam->engraving_stage_id = $newEgravingStage->id;
                            $newPlanParam->save();
                        }
                    }
        
                    $stageNumber += 1;
                }
            }
        }

        $engravingOrder =EngravingOrder::find($request->orderStage['engraving_order_id']);
        $engravingOrder->status = 'в работе';
        $engravingOrder->save();

        $engravingOrder = EngravingOrder::with([
            'engravingOrderStages.engravingStages.planParams.parameter',
            'engravingOrderStages.engravingStages.stage',
            'engravingOrderStages.engravingStages.operation.factParameters.parameter',
            'shaft',
            'design_order'
        ])->findOrFail($request->orderStage['engraving_order_id']);

        return $engravingOrder;
    }

    public function skipDefect(Request $request)
    {
        $skipStage = EngravingStage::where('engraving_order_stage_id',$request->orderStage['id'])->where('defect',true)->first();
        $skipStage->update(['defect' => false, 'post' => true]);

        if ($engravingNextStage = EngravingStage::where('engraving_order_stage_id',$request->orderStage['id'])->where('stage_number', '>', $skipStage->stage_number)->orderBy('engraving_order_stage_id')->orderBy('stage_number')->first()) {
            $engravingNextStage->update(['open' => true]);

            $engravingOrder =EngravingOrder::find($request->orderStage['engraving_order_id']);
            $engravingOrder->status = 'в работе';
            $engravingOrder->save();

            $engravingOrderStage =EngravingOrderStage::find($request->orderStage['id']);
            $engravingOrderStage->status = 'в работе';
            $engravingOrderStage->save();

        } else {
            $engravingOrder =EngravingOrder::find($request->orderStage['engraving_order_id']);
            $engravingOrder->status = 'завершен';
            $engravingOrder->save();

            $engravingOrderStage =EngravingOrderStage::find($request->orderStage['id']);
            $engravingOrderStage->status = 'завершен';
            $engravingOrderStage->save();

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
                ];
        
                foreach ($emails as $email) {
                    Mail::send('ugpc/mail_complete_engraving_order',['engraving_orders' => $allEngravingOrders],function($message) use ($email){
                        $message->to($email)->subject("Комплект валов готов");
                        $message->from('d.portal@danaflex.ru','UGPC-Portal');
                    });
                }
            
            }

        }

        $engravingOrder = EngravingOrder::with([
            'engravingOrderStages.engravingStages.planParams.parameter',
            'engravingOrderStages.engravingStages.stage',
            'engravingOrderStages.engravingStages.operation.factParameters.parameter',
            'shaft',
            'design_order'
        ])->findOrFail($request->orderStage['engraving_order_id']);

        return $engravingOrder;
    }

    public function replaceShaft(Request $request)
    {
        $engravingOrder = EngravingOrder::with([
            'engravingOrderStages.engravingStages.planParams.parameter',
            'engravingOrderStages.engravingStages.stage',
            'engravingOrderStages.engravingStages.operation.factParameters.parameter',
            'shaft',
            'design_order'
        ])->findOrFail($request->orderStage['engraving_order_id']);

        $emails = [
            'v.arsentev@danaflex.ru',
            'm.tinchurina@danaflex.ru',
            'd.biktimirov@danaflex.ru',
            'K_UGPC_NANO@danaflex.ru',
            'gravuring@danaflex.ru',
            'l.sadikova@danaflex.ru',
        ];

        $data = array('body'=>'Необходимо заменить вал '.$engravingOrder->shaft->shaft_id.'   '.'из оквида'.' '.$engravingOrder->design_order->okvid_number.'  '.'Комментарий:'.'  '.$engravingOrder?->lastEngravingOrderStage?->defectEngravingStage?->defect_note); 
        foreach ($emails as $email) {
            Mail::send('ugpc/mail_replace_shaft',$data,function($message) use ($email){
                $message->to($email)->subject("Замена вала");
                $message->from('d.portal@danaflex.ru','UGPC-Portal');
            });
        }

        return $engravingOrder;
    }
    

    public function createOrderStage(EngravingOrder $engravingOrder,$startDiameter,$endDiameter,$orderStageNumber)
    {
        //$baseGrindingDiameter = $engravingOrder->initial_size - 0.144;
        //$copperPlating = round($this->calculateCopperPlating($engravingOrder, $baseGrindingDiameter,$engravingOrder->final_size));

        $copperReserve = GlobalData::where('variable_name', 'copper_reserve')
        ->where('variable_desc', '<=', $engravingOrder->format)
        ->get()->last();


        $copperPlating = round($this->calculateCopperPlating($engravingOrder, $startDiameter,$endDiameter));

        if ($engravingOrder->type_shaft == 'Сталь') {
            $qtyStages = floor($copperPlating/350);

            $qtyStages = floor(($copperPlating+($qtyStages*$copperReserve->variable_body))/350);
        } else {
            $qtyStages = floor($copperPlating/500);

            $qtyStages = floor(($copperPlating+($qtyStages*$copperReserve->variable_body))/500);
        }

        if ($copperPlating <= 500) {
            if ($engravingOrder->type_shaft == 'Хром') {
                $orderStages = 
                [
                    [
                        'stages' => [1,3,4,12,7,9,8,10,11,13],
                        'baseGrindingDiameter' => round($this->calculateCopperPlating($engravingOrder,$startDiameter,$endDiameter)) >= 70 ? $startDiameter : round($endDiameter-(70*2/(1000 * 1.05)),3),
                        'CopperPlating' => round($this->calculateCopperPlating($engravingOrder,$startDiameter,$endDiameter)) >= 70 ? round($this->calculateCopperPlating($engravingOrder,$startDiameter,$endDiameter)) : 70,
                    ]
                ];
            } else if ($engravingOrder->type_shaft == 'Медь') {
                $orderStages = 
                [
                    [
                        'stages' => [3,4,12,7,9,8,10,11,13],
                        'baseGrindingDiameter' => round($this->calculateCopperPlating($engravingOrder,$startDiameter,$endDiameter)) >= 70 ? $startDiameter : round($endDiameter-(70*2/(1000 * 1.05)),3),
                        'CopperPlating' => round($this->calculateCopperPlating($engravingOrder,$startDiameter,$endDiameter)) >= 70 ? round($this->calculateCopperPlating($engravingOrder,$startDiameter,$endDiameter)) : 70,
                    ]
                ];
            } else if ($engravingOrder->type_shaft == 'Сталь') {
                if ($orderStageNumber == 1) {

                    if ($copperPlating <= 350) {
                        $orderStages = 
                        [
                            [
                                'stages' => [3,2,5,6],
                                'baseGrindingDiameter' => $startDiameter,
                                'baseCopperPlating' => round($this->calculateCopperPlating($engravingOrder,$startDiameter, $endDiameter - 0.144)),
                            ],
                            [
                                'stages' => [3,4,12,7,9,8,10,11,13],
                                'baseGrindingDiameter' => $endDiameter - 0.144,
                                'CopperPlating' => round($this->calculateCopperPlating($engravingOrder,$endDiameter - 0.144,$endDiameter)),
                            ]
                        ];
                    } else {
                        $orderStages = 
                        [
                            [
                                'stages' => [3,2,5,6],
                                'baseGrindingDiameter' => $startDiameter,
                                'baseCopperPlating' => 350,
                            ],
                            [
                                'stages' => [3,4,12,7,9,8,10,11,13],
                                'baseGrindingDiameter' => $endDiameter - 0.144,
                                'CopperPlating' => round($this->calculateCopperPlating($engravingOrder,$endDiameter - 0.144,$endDiameter)),
                            ]
                        ];
                    }
                } else {
                    $orderStages = 
                    [
                        [
                            'stages' => [3,4,12,7,9,8,10,11,13],
                            'baseGrindingDiameter' => $startDiameter,
                            'CopperPlating' => round($this->calculateCopperPlating($engravingOrder,$startDiameter,$endDiameter)),
                        ]
                    ];
                }
            }
        } else {
            
            $copperReserve = GlobalData::where('variable_name', 'copper_reserve')
                ->where('variable_desc', '<=', $engravingOrder->format)
                ->get()->last();

            $startDiameterStage = $startDiameter;
            if ($engravingOrder->type_shaft == 'Сталь') {
                $endDiameterStage = $startDiameter + 0.350*2;
            } else {
                $endDiameterStage = $startDiameter + 0.500*2;
            }

            $orderStages = [];

            $stageNum = 1;
            while ($qtyStages > 0) {

                if ($engravingOrder->type_shaft == 'Хром') {
                    if (($qtyStages - 1 == 0) && (round($this->calculateCopperPlating($engravingOrder,$endDiameterStage - ($copperReserve->variable_body/1000)*2,$endDiameter)) < 70)) {
                        $baseCopperPlating = round($this->calculateCopperPlating($engravingOrder,$startDiameterStage,$endDiameter - 0.07*2))-$copperReserve->variable_body;
                        $endDiameterStage = $startDiameterStage + $baseCopperPlating/1000*2;
                    } else {
                            $baseCopperPlating = 500;
                    }

                    if (($stageNum == 1) && ($orderStageNumber == 1)) {
                        $stage = 
                        [
                            'stages' => [1,3,2,6],
                            'baseGrindingDiameter' => $startDiameterStage,
                            'baseCopperPlating' => $baseCopperPlating,
                        ];
                    } else {
                        $stage = 
                        [
                            'stages' => [3,2,6],
                            'baseGrindingDiameter' => $startDiameterStage,
                            'baseCopperPlating' => $baseCopperPlating,
                        ];
                    }
                } else if ($engravingOrder->type_shaft == 'Медь') {
                    if (($qtyStages - 1 == 0) && (round($this->calculateCopperPlating($engravingOrder,$endDiameterStage - ($copperReserve->variable_body/1000)*2,$endDiameter)) < 70)) {
                        $baseCopperPlating = round($this->calculateCopperPlating($engravingOrder,$startDiameterStage,$endDiameter - 0.07*2))-$copperReserve->variable_body;
                        $endDiameterStage = $startDiameterStage + $baseCopperPlating/1000*2;
                    } else {
                        $baseCopperPlating = 500;
                    }

                    $stage = 
                        [
                            'stages' => [3,2,6],
                            'baseGrindingDiameter' => $startDiameterStage,
                            'baseCopperPlating' => $baseCopperPlating,
                        ];
                } else if ($engravingOrder->type_shaft == 'Сталь') {
                    if (($qtyStages - 1 == 0) && (round($this->calculateCopperPlating($engravingOrder,$endDiameterStage - ($copperReserve->variable_body/1000)*2,$endDiameter)) < 70)) {
                        $baseCopperPlating = round($this->calculateCopperPlating($engravingOrder,$startDiameterStage,$endDiameter - (0.07*2)))-$copperReserve->variable_body;
                        $endDiameterStage = $startDiameterStage + $baseCopperPlating/1000*2;
                    } else {


                        if ($stageNum == 1) {
                            $baseCopperPlating = 350;
                        } else {
                            $baseCopperPlating = 500;
                        }
                        
                    }

                    if ($stageNum == 1) {
                        $stage = 
                        [
                            'stages' => [3,2,5,6],
                            'baseGrindingDiameter' => $startDiameterStage,
                            'baseCopperPlating' => $baseCopperPlating,
                        ];
                    } else {
                        $stage = 
                        [
                            'stages' => [3,2,6],
                            'baseGrindingDiameter' => $startDiameterStage,
                            'baseCopperPlating' => $baseCopperPlating,
                        ];
                    }
                }
                array_push($orderStages,$stage);
        
                $startDiameterStage = $endDiameterStage - ($copperReserve->variable_body/1000)*2;
                $endDiameterStage = $startDiameterStage + $baseCopperPlating/1000*2;
                $qtyStages = $qtyStages - 1;
                $stageNum += 1;
            }

            $stage = [
                'stages' => [3,4,12,7,9,8,10,11,13],
                'baseGrindingDiameter' =>  $startDiameterStage,
                'CopperPlating' => round($this->calculateCopperPlating($engravingOrder,$startDiameterStage,$endDiameter)),
            ];
            array_push($orderStages,$stage);
        }

        $shaftorder = ShaftOrder::where('okvid_number',$engravingOrder->design_order->okvid_number)->where('shaft_id', $engravingOrder->shaft->shaft_id)->first();

        EngravingOrderStage::where('engraving_order_id',$engravingOrder->id)->where('stage_number','>',$orderStageNumber+$qtyStages)->delete();
        

        foreach ($orderStages as $orderStage) {

                $newEngravingOrderStage = EngravingOrderStage::where('engraving_order_id',$engravingOrder->id)->first();

                if (!$newEngravingOrderStage || $newEngravingOrderStage->status != null) {
                    $newEngravingOrderStage = EngravingOrderStage::updateOrCreate(
                        ['engraving_order_id' => $engravingOrder->id, 'stage_number' => $orderStageNumber,'status' => 'в работе'],
                        [   
                            'status' => 'в работе',
                        ],
                    );
                } else {
                    $newEngravingOrderStage->status = 'в работе';
                    $newEngravingOrderStage->save();
                }

                EngravingStage::where('engraving_order_stage_id',$newEngravingOrderStage->id)->whereNotIn('stage_id',$orderStage['stages'])->delete();
                $stageNumber = 1;
                foreach ($orderStage['stages'] as $idStage) {

                    //$stage = EngravingStage::where('engraving_order_stage_id',$newEngravingOrderStage->id)->where('stage_id',$idStage)->first();
                    $newEngravingStage = EngravingStage::updateOrCreate(
                        [
                            'engraving_order_stage_id' => $newEngravingOrderStage->id,
                            'stage_id' => $idStage
                        ],
                        [   
                            'stage_number' => $stageNumber,
                            'open' => $stageNumber == 1 && $engravingOrder->status == 'Подтвержден' && $orderStageNumber == 1 ? true : false,
                        ],
                    );

                    $stageNumber = $stageNumber+1;

                    $parameters = null;

                    switch ($idStage) {
                        case 3:
                            $parameters = [
                                ['parameter_id' => 1, 'float_value' => $orderStage['baseGrindingDiameter']],
                                ['parameter_id' => 3, 'string_value' => $engravingOrder->type_shaft == 'Сталь' && $orderStageNumber == 1 ? '9...10' : '0,6...0,8'],
                            ];
                            break;
                        case 6:
                            $parameters = [
                                ['parameter_id' => 5, 'float_value' => $orderStage['baseCopperPlating']],
                            ];
                            break;
                        case 7:
                            $parameters = [
                                ['parameter_id' => 1, 'float_value' => $engravingOrder->final_size],
                                ['parameter_id' => 4, 'string_value' => '200...230'],
                                ['parameter_id' => 3, 'string_value' => $shaftorder->panton == 'ColdSeal' || $shaftorder->panton == 'Cold seal' || $shaftorder->color == 'Coldseal' || $shaftorder->color == 'Cold seal' ? '1,1...1,2' : '0,5...0,6'],
                            ];
                            break;
                        case 9:
                            $parameters = [
                                ['parameter_id' => 8, 'string_value' => $engravingOrder->lineature],
                                ['parameter_id' => 9, 'string_value' => $engravingOrder->corner],
                                ['parameter_id' => 10, 'string_value' => $engravingOrder->cutter],
                                ['parameter_id' => 11, 'float_value' => $engravingOrder->separation_number],
                                ['parameter_id' => 7, 'string_value' => $engravingOrder->color],
                            ];
                            break;
                        case 10:
                            $parameters = [
                                ['parameter_id' => 5, 'float_value' => 7],
                            ];
                            break;
                        case 12:
                            $parameters = [
                                ['parameter_id' => 5, 'float_value' => $orderStage['CopperPlating']],
                            ];
                            break;
                        case 13:
                            $parameters = [
                                ['parameter_id' => 7, 'string_value' => $engravingOrder->color],
                                ['parameter_id' => 3, 'string_value' => $shaftorder->panton == 'ColdSeal' || $shaftorder->panton == 'Cold seal' || $shaftorder->color == 'Coldseal' || $shaftorder->color == 'Cold seal' ? '0,8...0,9' : '0,4...0,5'],
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
                }

                $orderStageNumber += 1;
            
        }
        

        $updateEngravingOrder = EngravingOrder::with([
            'engravingOrderStages.engravingStages.planParams.parameter',
            'engravingOrderStages.engravingStages.stage',
            'engravingOrderStages.engravingStages.operation.factParameters.parameter',
            'shaft',
            'design_order'
        ])->findOrFail($engravingOrder->id);

        if ($updateEngravingOrder->status == 'Подтвержден') {
            $updateEngravingOrder->status = 'в работе';
            $updateEngravingOrder->save();
        }

        return $updateEngravingOrder;
    }

    public function calculateCopperPlating($engravingOrder, $startDiameter, $endDiameter)
    {
        $copperReserve = GlobalData::where('variable_name', 'copper_reserve')
        ->where('variable_desc', '<=', $engravingOrder->format)
        ->get()->last();

        $copperPlating =(($endDiameter - $startDiameter) * 1000 * 1.05) / 2;

        if ($copperPlating > 150) {
            $copperPlating += $copperReserve->variable_body;
        }

        return $copperPlating;
    }

    public function acceptShaft(Request $request)
    {    
        $user = Auth::user();
        $workShiftCode = WorkShiftCode::where('open', true)->first();

        $engravingOrder = EngravingOrder::updateOrCreate(
            ['id' => $request->engraving_order_id],
            [   'initial_size' => $request->diameter ?? null,
                'type_shaft' => $request->type_shaft ?? null,
                'copper_thickness' => $request->copper_platting ?? null,
                'input_control' => true,
            ],
        );


        $newOperation = OperationLedgerEntry::updateOrCreate(
            ['engraving_order_id' => $engravingOrder->id, 'operation_id' => 1],
            [   'start_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s'),
                'end_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s'),
                'posting_date' => $workShiftCode->date ?? null,
                'work_shift_code' => $workShiftCode->code ?? null,
                'work_center_id' => 5,
                'machine_id' => 16,
                'user_id' => $user->id ?? $request->operator['id'],
            ],
        );

        if ($engravingOrder->status == 'Подтвержден') {
            $createEngravingOrder = $this->createOrderStage($engravingOrder,$engravingOrder->initial_size - 0.144,$engravingOrder->final_size,1);

            $engravingOrderStage = EngravingOrderStage::where('engraving_order_id',$request->engraving_order_id)->first();
            $firstEngravingStage = EngravingStage::where('engraving_order_stage_id',$engravingOrderStage->id)->first();
            $firstEngravingStage->update(['open' => true]);  
        }

        return 'ok';
    }

    public function infoShaft(Request $request)
    {
        $engravingOrder = EngravingOrder::with([
            'activeEngravingOrderStage.activeEngravingStage.planParams.parameter',
            'activeEngravingOrderStage.activeEngravingStage.stage.operations.params',
            'activeEngravingOrderStage.activeEngravingStage.operation.machine',
            'activeEngravingOrderStage.copperPlatingEditionStage.planCopperPlating',
            'shaft',
            'design_order'
        ])->find($request);

        return $engravingOrder;
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
