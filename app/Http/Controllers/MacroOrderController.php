<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{MacroOrder, EngravingOrder, Customer};
use Inertia\Inertia;

class MacroOrderController extends Controller
{
    public function index()
    {
        return Inertia::render('GravureDatabase/MacroOrder/Index', [
            'macroOrders' => MacroOrder::with('customer')->get(),
            'customers' => Customer::all(), // Пагинация для снижения нагрузки
        ]);
    }

    public function show(MacroOrder $macroOrder)
    {
        $firstEngravingOrder = $macroOrder->engravingOrders()->first();

        if (!$firstEngravingOrder) {
            return back()->with('error', 'Нет связанных заказов на гравировку.');
        }

        return redirect()->route('engravingOrders.show', [
            'macroOrder' => $macroOrder->id,
            'engravingOrder' => $firstEngravingOrder->id,
        ]);
    }

    public function create(Request $request)
    {
        $macroOrder = MacroOrder::create([
            'customer_id' => $request->customerId,
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
