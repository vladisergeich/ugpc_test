<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{RegravationApprovalRequest,MacroOrder,RegravationApprovalRequestShaft,RegravationStage,RegravationApprovalRequestStage};
use Inertia\Inertia;

class RegravationApprovalRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $applications = RegravationApprovalRequest::with('stages.stage', 'shafts')
        ->filter($request->all())
        ->get();
        
        return Inertia::render('RegravationApproval/Index', [
            'applications' => $applications,
            'customers' => RegravationApprovalRequest::getCustomersList(),
            'orders' => RegravationApprovalRequest::getOrdersList(),
            'stages' => RegravationStage::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(RegravationApprovalRequest $app)
    {
        return redirect()->route('engravingOrders.show', [
            'macroOrder' => $macroOrder->id,
            'engravingOrder' => $firstEngravingOrder->id,
        ]);
    }

    public function create(Request $request)
    {
        $app = RegravationApprovalRequest::updateOrCreate([
            'macro_order_id' => $request->macro['id'],
        ],['create_date' => now(),]);

        foreach ($request->shafts as $shaft) {
            $appShaft = RegravationApprovalRequestShaft::updateOrCreate([
                'regravation_approval_request_id' => $app->id,
                'engraving_order_shaft_id' => $shaft['id'],
            ],['reason_id' => $shaft['reason_id'] ?? null,  'note' => $shaft['note'] ?? null]);
        }


        $stages = RegravationStage::all();
    
        $stageNumber = 1;
        foreach ($stages as $stage) {
            $appStage = RegravationApprovalRequestStage::updateOrCreate(
                [
                    'regravation_approval_request_id' => $app->id,
                    'regravation_stage_id' => $stage->id
                ],
                [
                    'sequence_number' => $stageNumber,
                    'status' => null,
                ]
            );
    
            $stageNumber += 1;
        }


        return redirect()->route('regravation.index', [
            'applications' => RegravationApprovalRequest::all(),
        ]);
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
