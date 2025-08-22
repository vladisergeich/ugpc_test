<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{MacroOrder, EngravingOrder, Profile,Phase,PhaseStage,Item,Shaft,Order};
use Inertia\Inertia;
use App\Services\EngravingOrderService;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class EngravingOrderController extends Controller
{
    protected $engravingOrderService;
    protected $documentService;

    public function __construct(EngravingOrderService $engravingOrderService, DocumentService $documentService)
    {
        $this->engravingOrderService = $engravingOrderService;
        $this->documentService = $documentService;
    }

    public function show(MacroOrder $macroOrder, EngravingOrder $engravingOrder)
    {
        $engravingOrder->load([
            'engravingOrderShaft.shaft.warehouse',
            'order.items.item',
            'profile.primary',
            'profile.secondary',
            'profile.vendor',
            'layoutStreams',
            'repeatEngravingOrder',
        ]);

        return Inertia::render('GravureDatabase/EngravingOrder/Show', [
            'macroOrder' => $macroOrder->load('shaftsInWork.shaft.warehouse','shaftsInWork.engravingOrder','customer'),
            'engravingOrder' => $engravingOrder,
            'engravingOrders' => $macroOrder->engravingOrders,
        ]);
    }

    public function create(Request $request)
    {
        $macroOrderId = $request->macro_order_id;

        $lastEngravingOrder = EngravingOrder::where('macro_order_id', $macroOrderId)
            ->latest('id')
            ->select(['additional_options', 'parameters', 'type_work_parameters', 'technological_elements'])
            ->first();

        $engravingOrder = EngravingOrder::updateOrCreate(
            ['macro_order_id' => $macroOrderId, 'sequence_number' => EngravingOrder::where('macro_order_id', $macroOrderId)->max('sequence_number') + 1 ?? 1],
            $lastEngravingOrder ? $lastEngravingOrder->toArray() : []
        );

        return redirect()->route('engravingOrders.show', [
            'macroOrder' => $engravingOrder->macro_order_id,
            'engravingOrder' => $engravingOrder->id,
        ])->with([
            'message' => 'Дубль создан',
            'engravingOrder' => $engravingOrder->load([
                'engravingOrderShaft.shaft',
                'order.items.item',
                'profile.vendor',
                'layoutStreams'
            ]),
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $mode = $request->input('mode', 'batch');
        $results = [];
        
        if (strlen($search) > 2) {
            $results = $this->performSearch($search, $mode);
        }

        return Inertia::render('GravureDatabase/EngravingOrder/Show', [
            'results' => $results,
            'search' => $request->input('search'),
            'mode' => $request->input('mode'),
        ]);
    }
    
    protected function performSearch($query, $mode)
    {
        switch ($mode) {
            case 'product':
                return Item::where('code', 'like', "%{$query}%")
                ->with('engravingOrders.order')
                ->first()
                ?->engravingOrders;
            case 'shaft':
                $shafts = Shaft::where('code', 'like', "%{$query}%")
                ->with('engravingOrdersShafts.engravingOrder.order')
                ->get();
        
                return $shafts
                    ->flatMap(fn ($shaft) => $shaft->engravingOrdersShafts->pluck('engravingOrder'))
                    ->filter()
                    ->unique('id')
                    ->values();
            case 'batch':
                return Order::where('order_number', 'like', "%{$query}%")
                ->with('engravingOrders')
                ->first()
                ?->engravingOrders;
            default:
                return $this->searchByBatch($query);
        }
    }

    public function sendApplication(Request $request)
    {
        $engravingOrder = EngravingOrder::with([
            'macroOrder',
            'engraver',
            'engravingOrderShaft.shaft',
            'order.items.item',
            'profile.vendor'
        ])->findOrFail($request->id);
    
        DB::transaction(function () use ($engravingOrder) {
            $engravingOrder->engravingOrderShaft
                ->filter(fn($shaft) => $shaft->shaft_id)
                ->each(function ($shaft) {

                    $shaft->final_diameter = $shaft->calculateFinalDiameter();
                    $shaft->save();
    
                    $phase = Phase::updateOrCreate(
                        ['engraving_order_shaft_id' => $shaft->id, 'sequence' => 1],
                        ['status' => Phase::STATUS_IN_PROGRESS]
                    );
            
                    PhaseStage::updateOrCreate([
                        'phase_id' => $phase->id,
                        'sequence' => 1,
                        'stage_id' => 14,
                        'status' => 'in_progress',
                    ]);
                });
        });

        $xml_data_app_array = $engravingOrder->order->items
        ->pluck('xml_data_app')  
        ->collapse()                
        ->all(); 

        $xml_data_shaft_app_array = $engravingOrder->engravingOrderShaft
        ->pluck('xml_data_shaft_app')  
        ->collapse()                
        ->all(); 
    
        $pdfData = [
            'engravingOrder' => $engravingOrder,
        ];

        $attachments = [
            $this->documentService->generateXml($xml_data_app_array, '1.xml'),
            $this->documentService->generateXml($xml_data_shaft_app_array, '2.xml'),
            $this->documentService->generatePdf('pdf.invoice', $pdfData),
        ];
    
        Http::attach('attachment', $attachments[0], '1.xml')
            ->attach('attachment', $attachments[1], '2.xml')
            ->attach('attachment', $attachments[2], $engravingOrder->pdf_name)
            ->post('http://10.10.40.94:4415/ws/zayavka');    

        $engravingOrder->update([
            'engraving_request_date' => now(),
        ]);

        return back()->with([
            'message' => 'Заявка отправлена',
            'pdf' => $attachments[2],
            'engravingOrder' => $engravingOrder,
        ]);
    }

    public function sendShaftInfo(Request $request)
    {
        $engravingOrder = EngravingOrder::with('engravingOrderShaft', 'order.items.item')
            ->findOrFail($request->id);

        $xml_array = $engravingOrder->engravingOrderShaft
            ->whereNotNull('shaft_id')
            ->pluck('xml_data_shaft')  
            ->collapse()                
            ->all(); 

        $xml_shafts = $this->documentService->generateXml($xml_array, 'EskoParam');

        Http::attach('attachment', $xml_shafts, '-ROTOParam.xml')
            ->post('http://10.10.40.94:4415/ws/zayavka');

        return back()->with([
            'message' => 'Информация по валам отправлена',
            'engravingOrder' => $engravingOrder,
        ]);
    }

    public function sendStreamInfo(Request $request)
    {
        $engravingOrder = EngravingOrder::with('layoutStreams', 'order.items.item')
            ->findOrFail($request->id);

        $xml_array = $engravingOrder->layoutStreams
            ->pluck('xml_data_streams')  
            ->collapse()                
            ->all(); 
        

        $xml_streams = $this->documentService->generateXml($xml_array, 'EskoVerst');

        Http::attach('attachment', $xml_streams,'+ROTOVerst.xml')
            ->post('http://10.10.40.94:4415/ws/EskoVerstTest');

        return back()->with([
            'message' => 'Информация по валам отправлена',
            'engravingOrder' => $engravingOrder,
        ]);
    }

    public function update(Request $request)
    {
        $engravingOrder = EngravingOrder::findOrFail($request->id);
        $engravingOrder->update($request->all());

        return back()->with([
            'message' => 'Updated successfully',
            'engravingOrder' => $engravingOrder,
        ]);
    }
}
