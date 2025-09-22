<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{MacroOrder,Phase, PhaseStage,EngravingOrder, EngravingOrderShaft,DefectLedgerEntry};
use Inertia\Inertia;
use App\Domain\Engraving\Services\EngravingStageService;

class RouteMapController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $query = EngravingOrder::query()
        ->with([
            'macroOrder.customer',
            'order.items.item',
            'engravingOrderShaft' => fn($q) => $q->whereNotNull('shaft_id'),
            'engravingOrderShaft.shaft',
            'engravingOrderShaft.phases'
        ])->whereHas('engravingOrderShaft', function($query) {
            $query->where('status', 'in_progress');
        });

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('macro_order_id', 'like', "%{$search}%");
            });
        }

        $engravingOrders = $query->paginate($perPage, ['*'], 'page', $page);
        
        return Inertia::render('RouteMap/Index', [
            'engravingOrders' => $engravingOrders->items(),
            'totalRecords' => $engravingOrders->total(),
            'filters' => $request->only(['search'])
        ]);
    }

    public function alterationStages(Request $request)
    {
        $phase = Phase::with('engravingOrderShaft','stages')->find($request->phase['id']);

        $selectedStageIds = collect($request->alterationStages)
        ->where('selected', true)
        ->pluck('id')
        ->toArray();
    
        $stages = [['stages' => array_map(function($id) {
            return $id;
        }, $selectedStageIds)]];

        if ($phase) {
            app(EngravingStageService::class)->updateOrCreateStages($phase->engravingOrderShaft, $stages ,$phase->sequence+1);
        }

        $phase = Phase::with('engravingOrderShaft','stages')->find($request->phase['id'])->update(['status' => 'completed']);

        $engravingOrderShaft = EngravingOrderShaft::with('engravingOrder','shaft','phases.stages.stage','phases.stages.parameters.parameter','phases.stages.operation.parameters.parameter','phases.stages.operation.workShiftCode')->find($phase->engravingOrderShaft->id);

        return Inertia::render('RouteMap/Show', [
            'engravingOrderShaft' => $engravingOrderShaft,
        ]);
    }

    public function skipDefect(Request $request)
    {
        $phase = Phase::with('engravingOrderShaft','stages')->find($request->phase['id'])->update(['status' => 'in_progress']);
        $stage = PhaseStage::where('phase_id',$request->phase['id'])->where('status','rejected')->update(['status' => 'in_progress']);

        DefectLedgerEntry::where('engraving_order_shaft_id',$phase->engravingOrderShaft->id)->where('phase_stage_id',$stage->id)->delete();

        $engravingOrderShaft = EngravingOrderShaft::with('engravingOrder','shaft','phases.stages.stage','phases.stages.parameters.parameter','phases.stages.operation.parameters.parameter','phases.stages.operation.workShiftCode')->find($phase->engravingOrderShaft->id);

        return Inertia::render('RouteMap/Show', [
            'engravingOrderShaft' => $engravingOrderShaft,
        ]);
    }

    public function replaceShaft(Request $request)
    {
        $engravingOrderShaft = EngravingOrderShaft::load('engravingOrder','shaft','phases.stages.stage','phases.stages.parameters.parameter','phases.stages.operation.parameters.parameter','phases.stages.operation.workShiftCode')->find($request->engravingOrderShaft->id);

        $emails = [
            'v.arsentev@danaflex.ru',
            'm.tinchurina@danaflex.ru',
            'd.biktimirov@danaflex.ru',
            'K_UGPC_NANO@danaflex.ru',
            'gravuring@danaflex.ru',
            'l.sadikova@danaflex.ru',
        ];

        $data = array('body'=>'Необходимо заменить вал '.$engravingOrderShaft->shaft->code.'   '.'из оквида'.' '.$engravingOrderShaft->engravingOrder->okvid_number.'  '.'Комментарий:'.'  '.$engravingOrder?->lastEngravingOrderStage?->defectEngravingStage?->defect_note); 
        foreach ($emails as $email) {
            Mail::send('ugpc/mail_replace_shaft',$data,function($message) use ($email){
                $message->to($email)->subject("Замена вала");
                $message->from('d.portal@danaflex.ru','UGPC-Portal');
            });
        }

        return Inertia::render('RouteMap/Show', [
            'engravingOrderShaft' => $engravingOrderShaft,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EngravingOrderShaft $engravingOrderShaft)
    {
        $engravingOrderShaft->load('engravingOrder','shaft','phases.stages.stage','phases.stages.parameters.parameter','phases.stages.operation.parameters.parameter','phases.stages.operation.workShiftCode');

        return Inertia::render('RouteMap/Show', [
            'engravingOrderShaft' => $engravingOrderShaft,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
