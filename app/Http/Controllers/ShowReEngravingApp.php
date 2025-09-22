<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ugpc\ReEngravingApplication;
use Illuminate\Support\Facades\Auth;

class ShowReEngravingApp extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id)
    {
        $user = Auth::user();
        
        $app = ReEngravingApplication::with('activeStage.approvStage')->find($id);

        $userPosition = $user->department;
    
        if (in_array($userPosition, json_decode($app->activeStage->approvStage->authorized_positions))) {
            return view('ugpc.reEngravingApp.application_card', [
                'application' => ReEngravingApplication::with('lastStages.approvStage','shafts.shaftOrder.order','activeStage.approvStage','shafts.lastOkvid.order','shafts.files','shafts.resourse','reEngravingStage.lastHistory.user','reEngravingStage.approvStage','reEngravingStage.stageHistory.stage.approvStage','reEngravingStage.stageHistory.user')->findOrFail($id),
                'message' => null,
            ]);
        } else {
            return view('ugpc.reEngravingApp.application_card', [
                'application' => ReEngravingApplication::with('lastStages.approvStage', 'shafts.shaftOrder.order', 'activeStage.approvStage', 'shafts.lastOkvid.order', 'shafts.files', 'shafts.resourse', 'reEngravingStage.lastHistory.user', 'reEngravingStage.approvStage', 'reEngravingStage.stageHistory.stage.approvStage', 'reEngravingStage.stageHistory.user')->findOrFail($id),
                'message' => 'Этап уже согласован, или у вас нет прав на его согласование',
            ]);
        }
    }
}
