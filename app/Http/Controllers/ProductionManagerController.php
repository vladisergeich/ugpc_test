<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ugpc\ExecutionPlan;

class ProductionManagerController extends Controller
{
    public function index()
    {
        return view('ugpc.production_manager.index');
    }

    public function getPlan(Request $request)
    {
        return response()->json([
            'plan' => ExecutionPlan::where('start_date',$request->date)->where('end_date',$request->date)->where('engraver_id',5)->first(),
        ]);
    }

    public function savePlan(Request $request)
    {
        $plan = ExecutionPlan::updateOrCreate(
            ['start_date' => $request->date,
             'engraver_id' => 5,
             'end_date' => $request->date,
            ],
            [   
                'plan_qty' => $request->plan,
            ],
        );

        return response()->json([
            'plan' => ExecutionPlan::where('start_date',$request->date)->where('end_date',$request->date)->where('engraver_id',5)->first(),
        ]);
    }

}
