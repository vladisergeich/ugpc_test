<?php

namespace App\Http\Controllers\Gravure;

use App\Domain\Engraving\Services\DirectoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gravure\MacroLookupRequest;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class GravureDirectoryController extends Controller
{
    public function __construct(private readonly DirectoryService $directoryService)
    {
    }

    public function index(): Response
    {
        return Inertia::render('GravureDatabase/GravureDatabasePage', [
            'macroOrders' => $this->directoryService->macroOrders(),
        ]);
    }

    public function getMacro(MacroLookupRequest $request): JsonResponse
    {
        $macroOrder = $this->directoryService->findMacroOrder($request->validated('macro'));

        return response()->json($macroOrder);
    }

    public function getProfiles(): JsonResponse
    {
        return response()->json($this->directoryService->profiles());
    }

    public function getVendors(): JsonResponse
    {
        return response()->json($this->directoryService->vendors());
    }

    public function getDesigners(): JsonResponse
    {
        return response()->json($this->directoryService->designers());
    }

    public function getCustomers(): JsonResponse
    {
        return response()->json($this->directoryService->customers());
    }

    public function getEngravingOrderStatuses(): JsonResponse
    {
        return response()->json($this->directoryService->engravingStatuses());
    }

    public function getEngravingOrderConditions(): JsonResponse
    {
        return response()->json($this->directoryService->engravingConditions());
    }

    public function getMountingParameters(): JsonResponse
    {
        return response()->json($this->directoryService->mountingParameters());
    }
}
