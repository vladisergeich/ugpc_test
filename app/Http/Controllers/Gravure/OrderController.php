<?php

namespace App\Http\Controllers\Gravure;

use App\Domain\Orders\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gravure\SaveOrderRequest;
use Illuminate\Http\RedirectResponse;
use Throwable;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function saveOrder(SaveOrderRequest $request): RedirectResponse
    {
        try {
            $engravingOrder = $this->orderService->saveOrder($request->toData());

            if (! $engravingOrder) {
                return redirect()->back()->withErrors([
                    'orderNumber' => 'Заказ не найден',
                ]);
            }

            return redirect()->back()->with([
                'message' => 'Партия загружена',
                'engravingOrder' => $engravingOrder,
            ]);
        } catch (Throwable $exception) {
            return redirect()->back()->withErrors([
                'error' => 'Ошибка при сохранении заказа',
                'details' => $exception->getMessage(),
            ]);
        }
    }
}
