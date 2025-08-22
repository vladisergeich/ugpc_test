<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{EngravingOrder,WorkShiftCode,Shaft,OperationLedgerEntry,EngravingOrderShaft};
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Services\EngravingOrderService;

class InputControlController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $query = EngravingOrder::query()
        ->with([
            'engravingOrderShaft' => fn($q) => $q->whereNotNull('shaft_id'),
            'engravingOrderShaft.engravingOrder',
            'engravingOrderShaft.shaft',
            'order.items.item',
            'macroOrder.customer'
        ])->whereHas('engravingOrderShaft', function($query) {
            $query->where('status', 'in_progress');
        });

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('macro_order_id', 'like', "%{$search}%");
            });
        }

        $engravingOrders = $query->paginate($perPage, ['*'], 'page', $page);
        
        return Inertia::render('InputControl/Index', [
            'engravingOrders' => $engravingOrders->items(),
            'totalRecords' => $engravingOrders->total(),
            'filters' => $request->only(['search'])
        ]);
    }

    public function acceptShaft(Request $request)
    {    
        $user = Auth::user();
        $workShiftCode = WorkShiftCode::where('status', 'processing')->first();

        $engravingOrderShaft = EngravingOrderShaft::find($request->engravingOrderShaftId);

        $shaft = Shaft::updateOrCreate(
            ['id' => $request->shaft_id],
            [   'diameter' => $request->diameter ?? null,
                'type' => $request->type_shaft ?? null,
                'thickness' => $request->copper_platting ?? null,
            ],
        );


        $newOperation = OperationLedgerEntry::updateOrCreate(
            ['engraving_order_shaft_id' => $engravingOrderShaft->id, 'operation_id' => 1],
            [   'starting_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s'),
                'ending_time' => now()->setTimezone('Europe/Moscow')->format('H:i:s'),
                'work_shift_code_id' => $workShiftCode->id ?? null,
                'work_center_id' => 5,
                'machine_id' => 1,
                'user_id' => $user->id ?? $request->operator['id'],
            ],
        );

        $newOperation->load('phaseStage.engravingOrderShaft');

        if ($engravingOrderShaft->parameters['confirmed']) {
            app(EngravingOrderService::class)->createStages($engravingOrderShaft, $newOperation, false);
        }

        return redirect()->route('mobileInputControl.index')->with('success', 'Лот успешно учтён');
    }

    public function infoShaft(Request $request)
    {
        $engravingOrderShaft = EngravingOrderShaft::with([
            'engravingOrder.order',
            'shaft',
        ])->find($request->shaftId);

        return Inertia::render('Mobile/InputControl/Index', [
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
    public function show(string $id)
    {
        //
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
