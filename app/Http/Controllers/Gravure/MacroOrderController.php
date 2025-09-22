<?php

namespace App\Http\Controllers\Gravure;

use App\Http\Controllers\Controller;
use App\Http\Requests\Gravure\CreateMacroOrderRequest;
use App\Models\Customer;
use App\Models\EngravingOrder;
use App\Models\MacroOrder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MacroOrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('GravureDatabase/MacroOrder/Index', [
            'macroOrders' => MacroOrder::with('customer')->get(),
            'customers' => Customer::all(),
        ]);
    }

    public function show(MacroOrder $macroOrder): RedirectResponse
    {
        $firstEngravingOrder = $macroOrder->engravingOrders()->first();

        if (! $firstEngravingOrder) {
            return back()->with('error', 'Нет связанных заказов на гравировку.');
        }

        return redirect()->route('engravingOrders.show', [
            'macroOrder' => $macroOrder->id,
            'engravingOrder' => $firstEngravingOrder->id,
        ]);
    }

    public function create(CreateMacroOrderRequest $request): RedirectResponse
    {
        $macroOrder = MacroOrder::create([
            'customer_id' => $request->validated('customer_id'),
            'create_date' => now(),
        ]);

        $engravingOrder = EngravingOrder::create([
            'macro_order_id' => $macroOrder->id,
        ]);

        return redirect()->route('engravingOrders.show', [
            'macroOrder' => $macroOrder->id,
            'engravingOrder' => $engravingOrder->id,
        ]);
    }
}
