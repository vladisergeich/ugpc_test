
<table>
<thead>
    <tr>
        <th>Вал</th>
        <th>Номер оквид</th>
        <th>Номер заказа</th>
        <th>Диаметр</th>
        <th>Толщина меди</th>
        <th>Твердость</th>
    </tr>
</thead>
<tbody>
    @foreach ($engraving_orders as $order)
        <tr>
            <td>{{$order->shaft->shaft_id}}</td>
            <td>{{ substr($order->design_order->okvid_number, 0, strlen($order->design_order->okvid_number) - 2) . '-' . substr($order->design_order->okvid_number, -2) }}</td>
            <td>{{ $order->design_order->prod_order }}</td>
            <td>{{ $order->lastEngravingOrderStage->grindingEditionStage->operation->factDiameter->float_value ?? ''}}</td>
            <td>{{ $order->lastEngravingOrderStage->copperPlatingEditionStage->operation->thickness->float_value ?? ''}}</td>
            <td>{{ $order->lastEngravingOrderStage->grindingEditionStage->operation->factHardness->float_value ?? ''}}</td>
        </tr>
    @endforeach
</tbody>
</table>