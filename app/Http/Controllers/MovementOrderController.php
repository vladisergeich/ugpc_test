<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{MovementOrder, Vendor, EngravingOrder};
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MovementOrderController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $startDate = $request->start_date ?? Carbon::today();
        $endDate = $request->end_date ?? Carbon::now()->lastOfMonth();
        $engraverId = $request->engraver_id ?? 5;

        $query = MovementOrder::query()
            ->where('status', 'in_progress')
            ->where('engraver_id', $engraverId)
            ->whereDate('completion_date', '>=', $startDate)
            ->whereDate('completion_date', '<=', $endDate)
            ->with([
                'related' => function ($morph) {
                    $morph->morphWith([
                        EngravingOrder::class => ['order.items.item', 'macroOrder.customer', 'condition', 'designer', 'engravingOrderShaft.shaft', 'engraver', 'repro', 'transferShaftEngraving', 'transferShaftPrinting']
                    ]);
                }
            ]);

        if ($request->search) {
            $query->whereHasMorph('related', [EngravingOrder::class], function ($q) use ($request) {
                $q->where('macro_order_id', 'like', "%{$request->search}%");
            });
        }

        $movementOrders = $query->orderBy('completion_date')->orderBy('priority_number')->get();
        
        return Inertia::render('MovementOrder/Index', [
            'movementOrders' => $movementOrders,
            'filters' => [
                'search' => $request->search,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'engraver_id' => $engraverId,
            ],
            'engravers' => Vendor::all(),
        ]);
    }

    public function getOrders(Request $request)
    {
        return response()->json(
            MovementOrder::where('status', 'await')
                ->with([
                    'related' => function ($morph) {
                        $morph->morphWith([
                            EngravingOrder::class => ['order', 'macroOrder.customer', 'condition']
                        ]);
                    }
                ])
                ->get()
        );
    }

    public function redistributeOrders(Request $request)
    {
        $startDate = Carbon::parse($request->dates[0]);
        $endDate = Carbon::parse($request->dates[1]);
        $dailyCapacity = (int) $request->qty;
    
        $movement_orders = MovementOrder::query()
            ->where('status', 'in_progress')
            ->where('engraver_id', $request->engraverId)
            ->whereBetween('completion_date', [$startDate, $endDate])
            ->with('related')
            ->orderBy('completion_date')
            ->orderBy('priority_number')
            ->get();
    
        $currentDate = clone $startDate;
        $overflowDate = (clone $endDate)->addDay(); // дата для остатка
        $dailyLoad = 0;
        $priority = 0;
    
        foreach ($movement_orders as $order) {
            $qty = $order->related->quantity_shaft ?? 0;
    
            if (($dailyLoad + $qty > $dailyCapacity) && ($currentDate != $overflowDate)) {
                $currentDate->addDay();
                $dailyLoad = 0;
                $priority = 0;
            }
    
            // если текущая дата вышла за пределы — фиксируем overflow дату
            if ($currentDate->gt($endDate)) {
                $currentDate = clone $overflowDate;
            }
    
            $priority++;
            $dailyLoad += $qty;
    
            $order->update([
                'completion_date' => $currentDate->copy(),
                'priority_number' => $priority,
            ]);
        }
    
        return redirect()->route('movementOrder.index');
    }

    public function addDownTime(Request $request)
    {
        $data = $request->validate([
            'completion_date' => ['required', 'date'],
            'engraver_id' => ['required', 'integer'],
            'note' => ['nullable', 'string'],
        ]);

        $priority = MovementOrder::where('engraver_id', $data['engraver_id'])
            ->whereDate('completion_date', $data['completion_date'])
            ->max('priority_number') ?? 0;

        MovementOrder::create([
            'completion_date' => $data['completion_date'],
            'engraver_id' => $data['engraver_id'],
            'note' => $data['note'] ?? null,
            'priority_number' => $priority + 1,
            'status' => MovementOrder::STATUS_IN_PROGRESS,
            'type' => 'downtime',
        ]);

        return back();
    }
    

    public function update(Request $request)
    {
        DB::transaction(function () use ($request) {
            collect($request)->groupBy('completion_date')->each(function ($orders, $date) {
                foreach ($orders as $index => $order) {
                    MovementOrder::where('id', $order['id'])->update([
                        'completion_date' => $date,
                        'priority_number' => $index + 1,
                        'engraver_id' => $order['engraver_id'],
                        'note' => $order['note'],
                        'status' => $order['status'],
                    ]);
                }
            });
        });

        return back();
    }

    public function destroy(string $id)
    {
        //
    }

    public function splitOrder(Request $request)
    {
        $order = MovementOrder::findOrFail($request->order_id);
        $splits = $request->splits; // [{date: '2024-07-01', quantity: 5}, {date: '2024-07-02', quantity: 5}]

        DB::transaction(function () use ($order, $splits) {
            foreach ($splits as $split) {
                MovementOrder::create([
                    'related_type' => $order->related_type,
                    'related_id' => $order->related_id,
                    'engraver_id' => $order->engraver_id,
                    'completion_date' => $split['date'],
                    'priority_number' => 1, // или рассчитать
                    'status' => $order->status,
                    'note' => $order->note,
                    'type' => $order->type,
                    'quantity_shaft' => $split['quantity'],
                ]);
            }
            $order->delete();
        });

        return redirect()->route('movementOrder.index');
    }
}
