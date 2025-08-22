<?php

namespace App\Services;

use App\Models\{Order, EngravingOrder, Profile, Item, OrderItem, ItemVariation};
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\SOAP\SoapClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\NavisionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected $navService;

    public function __construct(NavisionService $navService)
    {
        $this->navService = $navService;

        $this->textOrientationMap = [
            0 => '40', 1 => '44', 2 => '46',
            3 => '42', 4 => '41', 5 => '47'
        ];

        $this->photometkaMap = [
            1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D'
        ];
    }

    private function getWindingOption($orderData): string
    {
        $windingOption = $this->textOrientationMap[$orderData->text_orient] ?? '';

        if (isset($this->photometkaMap[$orderData->photometka])) {
            $windingOption .= $this->photometkaMap[$orderData->photometka];
        }

        return $windingOption;
    }

    public function saveOrder($request)
    {
        $orderData = $this->navService->getOrderData($request['orderNumber']);
        
        if (!$orderData) {
            return null;
        }

        // Запрашиваем только нужное поле ID (ускоряет запрос)
        $customerId = DB::connection('mdm')
            ->table('system_navision_customers')
            ->where('no', $orderData->customer_number)
            ->value('id');

        // Обновляем/создаём заказ
        $order = Order::updateOrCreate(
            ['order_number' => $orderData->No],
            [
                'customer_id' => $customerId,
                'status' => $orderData->order_status ?? null,
                'support_manager_code' => $orderData->otv_men ?? null,
                'support_manager_name' => $orderData->otv_men_name ?? null,
                'print_machine_center' => $orderData->print_machine ?? null,
                'winding_option' => $this->getWindingOption($orderData),
                'customer_shipment_date' => $orderData->Date_complete ?? null,
                'previous_order_number' => $orderData->No_Previous ?? null,
                'current_stage_approval' => $orderData->StageOrder ?? null,
                'next_order_number' => $orderData->NextOrder ?? null,
                'edit_design_parameters' => json_encode([
                    'new_panton' => $orderData->new_panton ?? false,
                    'textnew' => $orderData->textnew ?? false,
                    'ch_geometry' => $orderData->ch_geometry ?? false,
                    'ch_color_new_panton' => $orderData->ch_color_new_panton ?? false,
                    'ch_CMYK' => $orderData->ch_CMYK ?? false,
                    'ch_color_old_panton' => $orderData->ch_color_old_panton ?? false,
                    'perekomponovka' => $orderData->perekomponovka ?? false,
                    'ch_material' => $orderData->ch_material ?? false,
                    'ch_tech_izgotov' => $orderData->ch_tech_izgotov ?? false,
                ]),
            ]
        );

        $itemsData = is_array($orderData->ProdOrderLine->Prod_Order_Line_List ?? []) 
                    ? $orderData->ProdOrderLine->Prod_Order_Line_List 
                    : [$orderData->ProdOrderLine->Prod_Order_Line_List];

        // Готовим массив для batch insert
        $itemsToInsert = [];
        $orderItemsToInsert = [];

        foreach ($itemsData as $itemData) {
            
            if (!isset($itemData->Design_Count) && !isset($itemData->Print_Standard) ) {
                continue;
            }

            $designCount = max(1, $itemData->Design_Count); // Минимум 1

            $count = $designCount > 1 ? 1 : 0;
            for ($i = $count; $i < $designCount; $i++) {
                $item = Item::updateOrCreate(
                    ['code' => $itemData->Item_No, 'subspecies' => $i],
                    [
                        'reduction' => Str::after($itemData->Item_No, "ГП"),
                        'description' => $itemData->Description ?? null,
                        'category' => $itemData->Product_Category ?? null,
                        'brand' => $itemData->Brand ?? null,
                        'customer' => $customerId ? DB::connection('mdm')->table('system_navision_customers')->where('id', $customerId)->value('name') : null,
                        'sap_code' => $itemData->SAP_code ?? null,
                    ]
                );

                $orderItem = OrderItem::updateOrCreate(
                    [
                        'order_id' => $order->id,
                        'item_id' => $item->id,
                    ],
                    ['streams_quantity' => $itemData->kolvor]
                );
            }
        }

        $profileId = Profile::where('code', $itemsData[0]->Print_Standard ?? null)->value('id');
        
        // Оптимизация floatval и preg_replace
        $format = isset($orderData->format) ? floatval(preg_replace('/\D/', '', $orderData->format)) : null;
        $printStep = $orderData->shagpechati ?? null;
        
        $engravingOrder = EngravingOrder::updateOrCreate(
            [
                'macro_order_id' => $request['engravingOrder']['macro_order_id'],
                'sequence_number' => $request['engravingOrder']['sequence_number']
            ],
            [
                'profile_id' => $profileId,
                'order_id' => $order->id,
                'format' => $format,
                'material_width' => $orderData->Shirina1 ?? null,
                'stream_width' => $orderData->ShirRuch ?? null,
                'print_step' => $printStep,
                'streams_quantity' => $orderData->KolvoRuch ?? null,
                'fragments_in_circulation' => ($format && $printStep) ? $format / $printStep : null,
            ]
        );

        return $engravingOrder;
    }

}
