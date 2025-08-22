<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\{Shaft, MacroOrder, Item, EngravingOrderShaft,Warehouse};

class RegisterController extends Controller
{
    private function getDataForTab(string $tab)
    {
        return match ($tab) {
            'shafts' => Shaft::with(['vendor', 'warehouse'])->get(),
            'orderShafts' => MacroOrder::whereHas('shaftsInWork', fn($q) => 
                $q->where('engraving_order_shafts.status', 'in_progress')
                ->with('shaft.warehouse','engravingOrder')
            )->with(['shaftsInWork' => fn($q) => 
                $q->where('engraving_order_shafts.status', 'in_progress')
                ->with('shaft.warehouse','engravingOrder')
            ])->get(),
            'items' => Item::all(),
            'formats' => EngravingOrderShaft::with(['engravingOrder.order', 'shaft.vendor'])->where('status','in_progress')->get(),
            default => [],
        };
    }

    public function shafts(Request $request)
    {
        $shafts = Shaft::query()
            ->when($request->search, fn($q) => $q->where('code', 'like', "%{$request->search}%"))
            ->when($request->format, fn($q, $formats) => $q->whereIn('format', $formats))
            ->with('warehouse')
            ->paginate(10)
            ->withQueryString();
    
        return Inertia::render('GravureDatabase/Register/Index', [
            'activeTab' => 'shafts',
            'items' => $shafts,
            'states' => Shaft::states(),
            'statuses' => Shaft::statuses(),
            'warehouses' => Warehouse::all(),
            'formats' => Shaft::pluck('format')
                ->unique()
                ->mapWithKeys(fn($f) => [$f => $f])
                ->toArray(),
            'filters' => $request->only(['search', 'format']),
        ]);
    }

    public function orderShafts(Request $request)
    {
        $orderShafts = MacroOrder::whereHas('shaftsInWork', fn($q) => 
                $q->where('engraving_order_shafts.status', 'in_progress')
                ->with('shaft.warehouse','engravingOrder')
            )->with(['shaftsInWork' => fn($q) => 
                $q->where('engraving_order_shafts.status', 'in_progress')
                ->with('shaft.warehouse','engravingOrder')
            ])->when($request->search, fn($q) => $q->where('code', 'like', "%{$request->search}%"))->paginate(10)
            ->withQueryString();

        return Inertia::render('GravureDatabase/Register/Index', [
            'activeTab' => 'orderShafts',
            'items' => $orderShafts,
            'filters' => $request->only('search'),
        ]);
    }

    public function items(Request $request)
    {
        $items = Item::query()
            ->when($request->search, fn($q) => $q->where('code', 'like', "%{$request->search}%"))
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('GravureDatabase/Register/Index', [
            'activeTab' => 'items',
            'items' => $items,
            'filters' => $request->only('search'),
        ]);
    }

    public function formats(Request $request)
    {
        $formats = EngravingOrderShaft::query()
        ->whereNotNull('shaft_id')
        ->with(['engravingOrder.order', 'shaft.vendor'])
        ->join('engraving_orders', 'engraving_order_shafts.engraving_order_id', '=', 'engraving_orders.id')
        ->orderBy('engraving_order_shafts.shaft_id')
        ->orderBy('engraving_orders.engraving_request_date')
        ->select('engraving_order_shafts.*')
        ->paginate(20)
        ->withQueryString();

        return Inertia::render('GravureDatabase/Register/Index', [
            'activeTab' => 'formats',
            'items' => $formats,
            'filters' => $request->only('search'),
        ]);
    }

    public function getHistory(Request $request)
    {
        $validated = $request->validate([
            'shaft' => 'required|integer|exists:shafts,id',
        ]);

        $history = EngravingOrderShaft::with(['shaft', 'engravingOrder.order','engravingOrder.engraver'])
            ->where('shaft_id', $validated['shaft'])
            ->get();

        return response()->json($history);
    }
}
