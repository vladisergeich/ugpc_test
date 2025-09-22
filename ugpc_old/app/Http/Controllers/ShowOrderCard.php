<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ugpc\EngravingOrder;
use App\Models\Ugpc\Stage;

class ShowOrderCard extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        return view('ugpc.ordercard', [
                'engraving_order' => EngravingOrder::with([
                'engravingOrderStages.engravingStages.planParams.parameter',
                'engravingOrderStages.engravingStages.stage',
                'engravingOrderStages.engravingStages.operation.factParameters.parameter',
                'engravingOrderStages.engravingStages.operation.user',
                'engravingOrderStages.engravingStages.operation.workShift',
                'engravingOrderStages.engravingStages.operation.machine',
                'shaft',
                'design_order'
            ])->findOrFail($id)
        ]);
    }
}
