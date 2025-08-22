<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\SOAP\SoapClient;
use App\Models\{MacroOrder, Order, EngravingOrder,EngravingOrderShaft, Item, OrderItem, ItemVariation, Shaft,Phase,PhaseStage,LayoutConstructor};
use Carbon\Carbon;
use Illuminate\Support\Str;

class MigrateDataCommand extends Command
{
    protected $signature = 'migrate:data'; // Команда запуска
    protected $description = 'Перенос данных из старой базы в новую';

    public function handle()
    {
        $this->info('🔄 Начинаем перенос данных...');

        /*
        DB::table('designers')->truncate();
        
        $designers = DB::connection('old_mysql')
            ->table('designers')
            ->get();

        foreach ($designers as $designer) {
            DB::table('designers')->insert([
                'name' => $designer->fio,
                'reduction' => $designer->reduction,
            ]);
        }

        DB::table('vendors')->truncate();

        $vendors = DB::connection('old_mysql')
            ->table('vendors')
            ->get();

        foreach ($vendors as $vendor) {
            DB::table('vendors')->insert([
                'name' => $vendor->vendor_name,
            ]);
        }

        DB::table('materials')->truncate();

        $materials = DB::connection('old_mysql')
            ->table('materials')
            ->get();

        foreach ($materials as $material) {
            DB::table('materials')->insert([
                'name' => $material->description,
            ]);
        }

        DB::table('engraving_order_conditions')->truncate();

        $order_states = DB::connection('old_mysql')
            ->table('order_states')
            ->get();

        foreach ($order_states as $order_state) {
            DB::table('engraving_order_conditions')->insert([
                'name' => $order_state->order_state,
            ]);
        }

        DB::table('engraving_order_statuses')->truncate();

        $order_statuses = DB::connection('old_mysql')
            ->table('order_statuses')
            ->get();

        foreach ($order_statuses as $order_status) {
            DB::table('engraving_order_statuses')->insert([
                'name' => $order_status->order_status,
            ]);
        }

        DB::table('colors')->truncate();

        $colors = DB::connection('old_mysql')
            ->table('color_books')
            ->get();

        foreach ($colors as $color) {
            DB::table('colors')->insert([
                'code' => $color->color_code,
                'name' => $color->color_description,
            ]);
        }

        DB::table('mounting_parameters')->truncate();

        $mounting_parameters = DB::connection('old_mysql')
            ->table('mounting_parameters')
            ->get();

        foreach ($mounting_parameters as $mounting_parameter) {
            DB::table('mounting_parameters')->insert([
                'code' => $mounting_parameter->id,
                'name' => $mounting_parameter->description,
            ]);
        }

        DB::table('work_centers')->truncate();

        $work_centers = DB::connection('old_mysql')
            ->table('work_centers')
            ->get();

        foreach ($work_centers as $work_center) {
            DB::table('work_centers')->insert([
                'code' => $work_center->work_center,
            ]);
        }

        DB::table('stages')->truncate();
        
        $stages = DB::connection('old_mysql')
            ->table('stages')
            ->get();

        foreach ($stages as $stage) {
            $workCenter = DB::table('work_centers')->where('code',$stage->work_center)->first();

            DB::table('stages')->insert([
                'name' => $stage->description,
                'work_center_id' => $workCenter->id,
            ]);
        }

        DB::table('operations')->truncate();
        
        $operations = DB::connection('old_mysql')
            ->table('operations')
            ->get();

        foreach ($operations as $operation) {
            $workCenter = DB::table('work_centers')->where('code',$operation->work_center)->first();

            DB::table('operations')->insert([
                'name' => $operation->description,
                'type' => $operation->type,
                'work_center_id' => $workCenter->id,
            ]);
        }

        DB::table('parameters')->truncate();
        
        $parameters = DB::connection('old_mysql')
            ->table('parameters')
            ->get();

        foreach ($parameters as $parameter) {
            DB::table('parameters')->insert([
                'name' => $parameter->title,
                'type' => $parameter->type,
            ]);
        }

        DB::table('stage_operations')->truncate();
        
        $stage_operations = DB::connection('old_mysql')
            ->table('stage_operations')
            ->get();

        foreach ($stage_operations as $stage_operation) {
            DB::table('stage_operations')->insert([
                'stage_id' => $stage_operation->stage_id,
                'operation_id' => $stage_operation->operation_id,
            ]);
        }

        
        DB::table('operation_parameters')->truncate();
        
        $operation_parameters = DB::connection('old_mysql')
            ->table('parameter_operations')
            ->get();

        foreach ($operation_parameters as $operation_parameter) {
            DB::table('operation_parameters')->insert([
                'operation_id' => $operation_parameter->operation_id,
                'parameter_id' => $operation_parameter->parameter_id,
            ]);
        }

        DB::table('profiles')->truncate();

        $profiles = DB::connection('old_mysql')
        ->table('profiles')
        ->get();

        foreach ($profiles as $profile) {
            $engraver = DB::table('vendors')->where('name',$profile->engraver)->first();
            $primary = DB::table('materials')->where('name',$profile->primary)->first();
            $secondary = DB::table('materials')->where('name',$profile->secondary_housing)->first();
            DB::table('profiles')->insert([
                'code' => $profile->short_name ?? null,
                'print_type' => $profile->print ?? null,
                'supplier_analog_icc' => $profile->supplier_analog_icc ?? null,
                'engraver_id' => $engraver->id ?? null,
                'primary_material_id' => $primary->id ?? null,
                'secondary_material_id' => $secondary->id ?? null,
                'paint_series' => $profile->paint_series,
            ]);
        }

                
        $shafts_old = DB::connection('old_mysql')->table('shafts')->get();


        foreach ($shafts_old as $shaft) {

            if (DB::table('shafts')->where('code',$shaft->shaft_id)->doesntExist())  {

                $vendor = DB::table('vendors')->where('name',$shaft->manufacturer ?? null)->first();
                $warehouse = DB::table('vendors')->where('name',$shaft->warehouse ?? null)->first();
                $engraving_order_old = DB::connection('old_mysql')->table('engraving_orders')->where('shaft_id',$shaft->id)->orderBy('id','desc')->first();

                Shaft::insert([
                    'code' => $shaft->shaft_id,
                    'format' => $shaft->shaft_format ?? null,
                    'ff' => $shaft->ff ?? null,
                    'manufacture_date' => $shaft->manufacture_date ?? null,
                    'vendor_id' => $vendor->id ?? null,
                    'status' => Shaft::STATUS_IN_NORMAL,
                    'state' => (bool) $shaft->free == true ? Shaft::STATE_IN_FREE : Shaft::STATE_ENGRAVING,
                    'warehouse_id' => $warehouse->id ?? null,
                    'warehouse_place' => $shaft->warehouse_place ?? null,
                    'type' => $engraving_order_old->type_shaft ?? 'Хром',
                    'thickness' => $engraving_order_old->copper_thickness ?? null,
                    'diameter' => $shaft->diameter ?? null,
                    'note' => $shaft->note ?? null,
                ]);

            }
        }
        
        */
        
        /*
        DB::table('macro_orders')->truncate();

        $n = 1;
        while ($n <= 6217) {
            $macro_order = DB::connection('old_mysql')
            ->table('macro_orders')
            ->where('macro_id',$n)
            ->first();

            if ($macro_order) {
                $client = DB::connection('mdm')
                ->table('system_navision_customers')
                ->where('name', 'LIKE', "%{$macro_order->customer}%")
                ->first();
            }


            DB::table('macro_orders')->insert([
                'customer_id' => $client->id ?? null,
                'create_date' => optional($macro_order)->create_date 
            ?? (optional($macro_order)->created_at ? Carbon::parse($macro_order->created_at)->toDateString() : null),
                'note' => $macro_order->note ?? null,
            ]);

            $n= $n+1;
        }
        */
        
        DB::table('engraving_orders')->truncate();
        
        $macro_orders = DB::table('macro_orders')->get();

        foreach ($macro_orders as $macro_order) {

            $orders = DB::connection('old_mysql')
            ->table('orders')
            ->where('upak_order',$macro_order->id)
            ->get();

            $n = 0;
            foreach ($orders as $order) {

                if (DB::table('orders')->where('order_number',$order->prod_order)->doesntExist())  {

                    DB::table('orders')->insert([
                        'order_number' => $order->prod_order,
                    ]);
                }

                $order_number = DB::table('orders')->where('order_number',$order->prod_order)->first();


                    $repro = DB::table('vendors')->where('name',$order->repro)->first();
                    $engraver = DB::table('vendors')->where('name',$order->engraving)->first();
                    $designer = DB::table('designers')->where('name',$order->designer_new)->first();
                    $application_install_completed = DB::table('designers')->where('name',$order->application_install_completed)->first();
                    $order_status = DB::table('engraving_order_statuses')->where('name',$order->order_status)->first();
                    $order_condition = DB::table('engraving_order_conditions')->where('name',$order->condition)->first();
                    $old_macro = DB::connection('old_mysql')->table('macro_orders')->where('macro_id',$order->upak_order)->first();
                    $profile = DB::table('profiles')->where('code',$order->profile)->first();

                    $mounting_parameter = DB::table('mounting_parameters')->where('name',$old_macro->crosses_bleed ?? null)->first();

                    if ($order->test_number) {
                        [$mainPart, $lastTwo] = [(int) substr($order->test_number, 0, -2), (int) ltrim(substr($order->test_number, -2), '0')];

                        $repeat_engraving_order = DB::table('engraving_orders')->where('macro_order_id',$mainPart)->where('sequence_number',$lastTwo)->first();
                    }
        
                    EngravingOrder::updateOrCreate(
                    [
                        'sequence_number' => $n,
                        'macro_order_id' => $macro_order->id,
                    ],
                    [
                        'order_id' => $order_number->id,
                        'profile_id' => $profile->id ?? null,
                        'status_id' => $order_status->id ?? null,
                        'condition_id' => $order_condition->id ?? null,
                        'repro_id' => $repro->id ?? null,
                        'engraver_id' => $engraver->id ?? null,
                        'designer_id' => $designer->id ?? null,
                        'gradation_increase' => $order->gradation_increase ?? null,
                        'order_approval_date' => $order->request_engraving_date ?? null,
                        'cutting_line_color' => $order->cut_line_color ?? null,
                        'printing_date' => $order->printing_date ?? null,
                        'engraving_request_user_id' => $application_install_completed->id ?? null,
                        'quantity_shaft' => $order->cylinder_quantity ?? null,
                        'engraving_request_date' => $order->request_install_date ?? null,
                        'repeat_engraving_order_id' => $repeat_engraving_order->id ?? null,
                        'format'=> $order->format ?? null,
                        'material_width' => $order->material_width ?? null,
                        'stream_width' => $order->width_stream,
                        'print_step' => $order->phototag_step,
                        'streams_quantity' =>  $order->amount_stream,
                        'fragments_in_circulation' => $order->fragment_in_circulation,
                        'sleeve_length' => $order->sleeve_lenght,
                        'engraving_width' => $order->width_engraving,
                        'mounting_parameter_id' => $mounting_parameter->id ?? null,
                        //'previous_engraving_order_id' => 
                        'comments' => [
                            'internal_comment' => $order->internal_comment?? null,
                            'engraver_comment' => $order->external_comment ?? null,
                        ],
                        'type_work_parameters' => [
                            'new_job' => (bool) ($order->new_job ?? false),
                            'reengraving' => (bool) ($order->order_reengraving ?? false),
                            'engraving_with_change' => (bool) ($order->engraving_with_change ?? false),
                            'installation' => (bool) ($order->prepress ?? false),
                            'color_proof' => (bool) ($order->color_proof ?? false),
                            'test_print' => (bool) ($order->test_print ?? false),
                            
                        ],
                        'technological_elements' => [
                            'drive_label_right' => (bool) ($order->drive_label_right ?? false),
                            'drive_label_left' => (bool) ($order->drive_label_left ?? false),
                            'traffic_lights_right' => (bool) ($order->traffic_lights_right ?? false),
                            'traffic_lights_left' => (bool) ($order->traffic_lights_left ?? false),
                            'cross_right' => (bool) ($order->cross_right ?? false),
                            'cross_left' => (bool) ($order->cross_left ?? false),
                            'cutting_line_right' => (bool) ($order->cutting_line_right ?? false),
                            'cutting_line_left' => (bool) ($order->cutting_line_left ?? false),           
                        ],
                        'additional_options' => [
                            'automatic_stream' => (bool) ($old_macro->auto_offset ?? false),
                            'gap' => (bool) ($old_macro->zazor ?? false),
                            'rail' => (bool) ($old_macro->relsa ?? false),
                            'without_cutting' => (bool) ($old_macro->bez_reza ?? false),
                            'rotate_factor' => (bool) ($old_macro->rotate_factor ?? false),
                            'label_coldseal' => (bool) ($order->marker_cs ?? false),
                            'chevrons' => (bool) ($order->shevrony ?? false),
                            'centering line' => (bool) ($order->сentering_line ?? false),       
                        ],
                        'parameters' => [
                            'universal_shaft' => (bool) false,
                            'approve_coordinator' => (bool) false,   
                            'updated_application' => (bool) ($order->print ?? false), 
                            'without_unloading_application' => (bool) ($order->no_folder ?? false),     
                        ],
                    ]);
                
                

                $n = $n+1;
            }
        }
        
        

        /*
        DB::table('engraving_order_shafts')->truncate();

        DB::connection('old_mysql')->table('shaft_orders')->orderBy('id')->chunk(500, function ($shafts_order) {
            $shaft_ids = $shafts_order->pluck('shaft_id')->unique()->filter()->toArray();
            $okvid_numbers = $shafts_order->pluck('okvid_number')->unique()->filter()->toArray();
        
            $shafts = DB::table('shafts')->whereIn('code', $shaft_ids)->get()->keyBy('code');
            $orders = DB::connection('old_mysql')->table('orders')->whereIn('okvid_number', $okvid_numbers)->get()->keyBy('okvid_number');
            $old_shafts = DB::connection('old_mysql')->table('shafts')->whereIn('shaft_id', $shaft_ids)->get()->keyBy('shaft_id');
        
            $insertData = [];
        
            foreach ($shafts_order as $shaft_order) {
                $shaft_new = $shafts[$shaft_order->shaft_id] ?? null;
        
                $mainPart = (int) substr($shaft_order->okvid_number, 0, -2);
                $lastTwo = (int) ltrim(substr($shaft_order->okvid_number, -2), '0');
        
                $engraving_order = DB::table('engraving_orders')
                    ->where('macro_order_id', $mainPart)
                    ->where('sequence_number', $lastTwo)
                    ->first();
        
                if (!$engraving_order) continue;
        
                $old_order = $orders[$shaft_order->okvid_number] ?? null;
                $old_shaft = $old_shafts[$shaft_order->shaft_id] ?? null;
        
                $old_engraving_order = DB::connection('old_mysql')->table('engraving_orders')
                    ->where('design_order_id', $old_order->id ?? null)
                    ->where('shaft_id', $old_shaft->id ?? null)
                    ->first();
        
                $insertData[] = [
                    'sequence_number' => $shaft_order->shaft_order_number,
                    'engraving_order_id' => $engraving_order->id,
                    'shaft_id' => $shaft_new->id ?? null,
                    'status' => (!$shaft_new) ? null : (((bool) $shaft_order->written_off == true) ? EngravingOrderShaft::STATUS_WRITTEN_OFF : EngravingOrderShaft::STATUS_IN_PROGRESS),
                    'color' => (($shaft_order->color == 'empty') || ($shaft_order->panton == 'empty')) ? null : ($shaft_order->color ?? $shaft_order->panton),
                    'lineature' => $shaft_order->lineature ?? null,
                    'corner' => $shaft_order->corner ?? null,
                    'cutter' => $shaft_order->cutter ?? null,
                    'note' => $shaft_order->note ?? null,
                    'write_off_date' => $shaft_order->writeoff_date ?? null,
                    'engraving_time' => $old_engraving_order->engraving_time ?? null,
                    'parameters' => json_encode([
                        'reengraving' => (bool) ($shaft_order->dispatch ?? false),
                        'shortening_scale_length' => (bool) ($shaft_order->base ?? false),
                        'confirmed' => (bool) false,
                        'final_diameter' => null,
                    ]),
                ];
            }
        
            if (!empty($insertData)) {
                EngravingOrderShaft::upsert($insertData, ['engraving_order_id', 'sequence_number'], ['status', 'color', 'lineature', 'corner', 'cutter', 'note', 'write_off_date', 'engraving_time', 'parameters']);
            }
        });
        
        
        

        
        $orders = DB::table('orders')->get();

        foreach ($orders as $order) {

            $prefix = strtolower(mb_substr($order->order_number, 0, 1));
            $place = match ($prefix) {
                'a' => env('NAV_ALABUGA_PAGE_SERVICE_URL').'ProdOrderAndLinePage',
                'n' => env('NAV_NANO_PAGE_SERVICE_URL').'ProdOrderAndLinePage',
                default => env('NAV_ZAO_PAGE_SERVICE_URL').'ProdOrderAndLinePage',
            };
    
            $options = [
                'login' => env('SOAP_NAV_USER'),
                'password' => env('SOAP_NAV_PASSWORD')
    
            ];
    
            $client = new SoapClient($place,$options);
            $result = $client->ReadMultiple(
                array( 
                    'filter'=>[ 
                        [
                        'Field' => 'No',
                        'Criteria' => "'{$order->order_number}'",
                        ],
                    ],
                    'setSize' => 1
                ) 
            );

            
            $orderData = $result->ReadMultiple_Result->ProdOrderAndLinePage ?? null;
            
            if ($orderData) {
                

                $client = DB::connection('mdm')
                    ->table('system_navision_customers')
                    ->where('no', $orderData->customer_number)
                    ->first();

                switch ($orderData->text_orient) {
                    case 0:
                        $winding_option = '40';
                        break;
                    case 1:
                        $winding_option = '44';
                        break;
                    case 2:
                        $winding_option = '46';
                        break;
                    case 3:
                        $winding_option = '42';
                        break;
                    case 4:
                        $winding_option = '41';
                        break;
                    case 5:
                        $winding_option = '47';
                        break;
                    default:
                        $winding_option = '';
                        break;
                }

                
                switch ($orderData->photometka) {
                    case 1:
                        $winding_option = $winding_option.'A';
                        break;
                    case 2:
                        $winding_option = $winding_option.'B';
                        break;
                    case 3:
                        $winding_option = $winding_option.'C';
                        break;
                    case 4:
                        $winding_option = $winding_option.'D';
                        break;
                    default:
                        $winding_option = $winding_option;
                        break;
                }

            
                $order = Order::updateOrCreate(['order_number' => $orderData->No],
                    [
                    'customer_id' => $client->id ?? null,
                    'status' => $orderData->order_status ?? null,
                    'support_manager_code' => $orderData->otv_men ?? null,
                    'support_manager_name' => $orderData->otv_men_name ?? null,
                    'print_machine_center' => $orderData->print_machine ?? null,
                    'winding_option' => $winding_option,
                    'customer_shipment_date' => $orderData->Date_complete ?? null,
                    'previous_order_number' => $orderData->No_Previous ?? null,
                    'current_stage_approval' => $orderData->StageOrder ?? null,
                    'next_order_number' => $orderData->NextOrder ?? null,
                    'edit_design_parameters' => json_encode([
                        'new_panton' => (bool) ($orderData->new_panton ?? false),
                        'textnew' => (bool) ($orderData->textnew ?? false),
                        'ch_geometry' => (bool) ($orderData->ch_geometry ?? false),
                        'ch_color_new_panton' => (bool) ($orderData->ch_color_new_panton ?? false),
                        'ch_CMYK' => (bool) ($orderData->ch_CMYK ?? false),
                        'ch_color_old_panton' => (bool) ($orderData->ch_color_old_panton ?? false),
                        'perekomponovka' => (bool) ($orderData->perekomponovka ?? false),
                        'ch_material' => (bool) ($orderData->ch_material ?? false),
                        'ch_tech_izgotov' => (bool) ($orderData->ch_tech_izgotov ?? false),   
                    ]),
                ]);

                
                if (isset($orderData->ProdOrderLine->Prod_Order_Line_List)) {
                
                    $itemsData = [$orderData->ProdOrderLine->Prod_Order_Line_List];
            
                    foreach ($itemsData as $itemData) {
                        
                        if (isset($itemData->Design_Count) && isset($itemData->Print_Standard)) {

                            if ($itemData->Design_Count > 1) {  
                                
                                for ($i = 1; $i <= $itemData->Design_Count; $i++) {
                                    $item = Item::updateOrCreate(
                                        ['code' => $itemData->Item_No,'subspecies' => $i],
                                        [
                                            'reduction' => Str::after($itemData->Item_No, "ГП"),
                                            'description' => $itemData->Description ?? null,
                                            'category' => $itemData->Product_Category ?? null,
                                            'brand' => $itemData->Brand ?? null,
                                            'customer' => $client->name ?? null,
                                            'sap_code' => $itemData->SAP_code ?? null,
                                        ]
                                    );
            
                                    $orderItem = OrderItem::updateOrCreate(
                                        ['order_id' => $order->id,'item_id' => $item->id],
                                        ['streams_quantity' => $itemData->kolvor]
                                    );
                                }
                            } else {
                                $item = Item::updateOrCreate(
                                    ['code' => $itemData->Item_No,'subspecies' => 0],
                                    [
                                        'reduction' => Str::after($itemData->Item_No, "ГП"),
                                        'description' => $itemData->Description ?? null,
                                        'category' => $itemData->Product_Category ?? null,
                                        'brand' => $itemData->Brand ?? null,
                                        'customer' => $client->name ?? null,
                                        'sap_code' => $itemData->SAP_code ?? null,
                                    ]
                                );
            
                                $orderItem = OrderItem::updateOrCreate(
                                    ['order_id' => $order->id,'item_id' => $item->id],
                                    ['streams_quantity' => $itemData->kolvor]
                                );
                            }
                        }
                    }
                }

            }
        }
        

        
        $layout_constructors = DB::connection('old_mysql')->table('layout_constructors')->orderBy('id')->get();

        foreach ($layout_constructors as $layout_constructor) {
            
            $order_old = DB::connection('old_mysql')->table('orders')->where('okvid_number',$layout_constructor->okvid_number)->first();

            $engraving_order = EngravingOrder::where('macro_order_id',$order_old->upak_order)->where('sequence_number',(int) substr($order_old->okvid_number, -2))->first();

            $item = Item::where('reduction',$layout_constructor->gp_code)->first();

            if ($item) {
                LayoutConstructor::updateOrCreate(['engraving_order_id' => $engraving_order->id,'stream_number' => $layout_constructor->stream_number],['item_id' => $item->id]);
            }
        }
        */

        
        $engraving_order_stages = DB::connection('old_mysql')->table('engraving_order_stages')->orderBy('id')->get();

        foreach ($engraving_order_stages as $engraving_order_stage) {
            $engraving_order_old = DB::connection('old_mysql')->table('engraving_orders')->where('id',$engraving_order_stage->engraving_order_id)->first();
            $order_old = DB::connection('old_mysql')->table('orders')->where('id',$engraving_order_old->design_order_id)->first();
            $shaft_old = DB::connection('old_mysql')->table('shafts')->where('id',$engraving_order_old->shaft_id)->first();

            $shaft = Shaft::where('code',$shaft_old->shaft_id)->first();
            $engraving_order = EngravingOrder::where('macro_order_id',$order_old->upak_order)->where('sequence_number',(int) substr($order_old->okvid_number, -2))->first();

            $engraving_order_shaft = EngravingOrderShaft::where('engraving_order_id',$engraving_order->id)->where('shaft_id',$shaft->id)->first();

            $phase = Phase::updateOrCreate(
                ['engraving_order_shaft_id' => $engraving_order_shaft->id,'sequence' => $engraving_order_stage->stage_number],
                ['status' => (!$engraving_order_stage->status) ? null : (($engraving_order_stage->status == 'завершен') ? Phase::STATUS_COMPLETED : (($engraving_order_stage->status == 'брак') ? Phase::STATUS_REJECTED : Phase::STATUS_IN_PROGRESS)),]
            );

            $engraving_stage = DB::connection('old_mysql')->table('engraving_stages')->where('engraving_order_stage_id',$engraving_order_stage->id)->first();

            if ($engraving_stage) {
                $phase_stage = PhaseStage::create(
                    ['phase_id' => $phase->id,'stage_id' => $engraving_stage->stage_id],
                    ['status' => ($engraving_stage->post) ? PhaseStage::STATUS_COMPLETED : (($engraving_stage->open) ? PhaseStage::STATUS_IN_PROGRESS : (($engraving_stage->defect) ? Phase::STATUS_REJECTED : null)),]
                );
            }
        }
        
        

        $this->info('✅ Данные успешно перенесены!');
    }
}
