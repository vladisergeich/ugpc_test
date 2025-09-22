
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
    @foreach ($route_cards as $card)
        <tr>
            <td>{{$card['shaft_id']}}</td>
            <td>{{ substr($card['okvid_number'], 0, strlen($card['okvid_number']) - 2) . '-' . substr($card['okvid_number'], -2) }}</td>
            <td>{{ $card['order_number'] }}</td>
            <td>{{ $card['grinding_edition_diameter_fact'] }}</td>
            <td>{{ round($card['copper_plating_edition_t_plan']) }}</td>
            <td>{{$card['grinding_edition_hv_fact']}}</td>
        </tr>
    @endforeach
</tbody>
</table>