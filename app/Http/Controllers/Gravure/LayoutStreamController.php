<?php

namespace App\Http\Controllers\Gravure;

use App\Domain\Engraving\Services\LayoutStreamService;
use App\Http\Controllers\Controller;
use App\Http\Requests\LayoutStreams\CreateLayoutStreamRequest;
use App\Http\Requests\LayoutStreams\DeleteLayoutStreamRequest;
use App\Http\Requests\LayoutStreams\UpdateLayoutStreamRequest;
use App\Models\EngravingOrder;
use App\Models\LayoutConstructor;

class LayoutStreamController extends Controller
{
    public function __construct(private readonly LayoutStreamService $streamService)
    {
    }

    public function create(CreateLayoutStreamRequest $request)
    {
        $engravingOrder = EngravingOrder::findOrFail($request->validated('engraving_order_id'));

        $updated = $this->streamService->addStreams(
            $engravingOrder,
            $request->validated('quantity')
        );

        return back()->with([
            'message' => 'Ручьи добавлены',
            'engravingOrder' => $updated,
        ]);
    }

    public function update(UpdateLayoutStreamRequest $request)
    {
        $layoutStream = LayoutConstructor::findOrFail($request->validated('id'));

        $engravingOrder = $this->streamService->updateStream(
            $layoutStream,
            $request->payload()
        );

        return back()->with([
            'message' => 'Ручей обновлен',
            'engravingOrder' => $engravingOrder,
        ]);
    }

    public function destroy(DeleteLayoutStreamRequest $request)
    {
        $layoutStream = LayoutConstructor::findOrFail($request->validated('id'));

        $engravingOrder = $this->streamService->deleteStream($layoutStream);

        return back()->with([
            'message' => 'Ручей удален',
            'engravingOrder' => $engravingOrder,
        ]);
    }
}
