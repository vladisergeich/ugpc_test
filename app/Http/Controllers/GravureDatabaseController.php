<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{MacroOrder, Designer, Customer, MountingParameter, Order, EngravingOrder,EngravingOrderShaft, Profile, Vendor, EngravingOrderStatus, EngravingOrderCondition};
use Inertia\Inertia;
use App\Services\GravureDatabaseService;


class GravureDatabaseController extends Controller
{
    protected $gravureDatabaseService;

    public function __construct(GravureDatabaseService $gravureDatabaseService)
    {
        $this->gravureDatabaseService = $gravureDatabaseService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('GravureDatabase/GravureDatabasePage', [
            'macroOrders' => MacroOrder::all(),
        ]);
    }

    public function getMacro(Request $request)
    {
        return response()->json(MacroOrder::with('shaftsInWork.shaft','shaftsInWork.engravingOrder','shaftsInWork.resources')->where('id',$request->macro)->first());
    }

    public function getProfiles()
    {
        return Profile::all();
    }

    public function getVendors()
    {
        return Vendor::all();
    }

    public function getDesigners()
    {
        return Designer::all();
    }

    public function getCustomers()
    {
        return Customer::groupBy('inn')->get();
    }

    public function getEngravingOrderStatuses()
    {
        return EngravingOrderStatus::all();
    }

    public function getEngravingOrderConditions()
    {
        return EngravingOrderCondition::all();
    }

    public function getMountingParameters()
    {
        return MountingParameter::all();
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
