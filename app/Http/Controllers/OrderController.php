<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function saveOrder(Request $request)
    {
        try {
            $engravingOrder = $this->orderService->saveOrder($request);
    
            if (!$engravingOrder) {
                return redirect()->back()->withErrors([
                    'orderNumber' => 'Заказ не найден',
                ]);
            }
    
            return redirect()->back()->with([
                'message' => 'Партия загружена',
                'engravingOrder' => $engravingOrder,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'error' => 'Ошибка при сохранении заказа',
                'details' => $e->getMessage()
            ]);
        }
    }
}
