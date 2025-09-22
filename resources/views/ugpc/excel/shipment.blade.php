<table>
    <thead>
    <tr>
        <th>Дата отгрузки</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid; height: 10px; padding: 5px;">
               {{$shaftrequest->shipping_date}}
            </td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
    <tr>
        <th>Заказ №</th>
        <th>Товар Но.</th>
        <th>Лот Но.</th>
        <th>Общее Кол-во</th>
        <th>Описание</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($shafts as $key => $composition)
        <tr>
            <td style="border: 1px solid; height: 10px; padding: 5px;">
               
            </td>
            <td style="border: 1px solid; height: 10px; padding: 5px; width: 60px;">
                Труба-клише
            </td>
            <td style="border: 1px solid; height: 10px; padding: 5px;">
             {{$key}}
            </td>
            <td style="border: 1px solid; height: 10px; padding: 5px; width: 60px;">
                1
            </td>
            <td style="border: 1px solid; height: 10px; padding: 5px;">
            {{$composition}}
            </td>
        </tr>
    @endforeach

    </tbody>
</table>