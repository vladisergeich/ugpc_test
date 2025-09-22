<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\OperationLedgerEntry;
use App\Models\EngravingOrder;
use App\Models\EngravingOrderShaft;
use App\Models\Shaft;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class MobileElectroPlatingController extends Controller
{
    public function index(Request $request)
    {
        $operator = Auth::user();
        // Получить текущие операции для оператора/цеха (пример)
        $runningOperations = OperationLedgerEntry::with(['machine', 'operation.parameters.parameter', 'engravingOrderShaft.shaft','phaseStage.parameters.parameter','phaseStage.operations.operation'])
            ->whereHas('machine', function($query) {
                $query->where('work_center_id', 1);
            })
            ->whereNull('ending_time')
            ->get();

        return Inertia::render('Mobile/Electroplating/Index', [
            'operator' => $operator,
            'runningOperations' => $runningOperations,
        ]);
    }

    public function infoShaft(Request $request)
    {
        $data = $request->validate(['shaftId' => ['required','integer']]);

        $eos = EngravingOrderShaft::with([
            'engravingOrder.order',
            'shaft',
            'activeStage.stage',
            'activeStage.phase',
            'activeStage.parameters.parameter',
            'activeStage.operations.operation',
        ])->find($data['shaftId']);
    
        if (!$eos) {
            throw ValidationException::withMessages(['shaftId' => 'Вал не найден']);
        }
    
        $stage = $eos->activeStage?->stage;
        if ($stage && (int)$stage->work_center_id !== 1) {
            return response()->json([
                'ok' => false,
                'code' => 'WRONG_STAGE',
                'stage' => [
                    'name' => $stage->name,
                    'work_center_id' => (int)$stage->work_center_id,
                ],
            ], Response::HTTP_CONFLICT); // 409
        }
    
        return response()->json([
            'ok' => true,
            'shaft' => $eos, // можно завернуть через ресурс
        ]);
    }
} 