
<h1>График на {{ $request->params['date'] }} число</h1>
<table>
<thead>
    <tr>
        <th>Дата готовности</th>
        <th>Вал</th>
        <th>Номер оквид</th>
        <th>Номер заказа</th>
        <th>Описание</th>
        <th>Склад</th>
    </tr>
</thead>
<tbody>
    @foreach ($movement_orders as $order)
        <tr>
            <td>{{ date('d.m.Y', strtotime($order->completion_date)) }}</td>
            <td>{{$order->shaft_id}}</td>
            <td>{{ substr($order->okvid_number, 0, strlen($order->okvid_number) - 2) . '-' . substr($order->okvid_number, -2) }}</td>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->description }}</td>
            <td>{{$order->warehouse}}</td>
        </tr>
    @endforeach
</tbody>
</table>

