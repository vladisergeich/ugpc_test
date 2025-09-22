<div class="container">
    <div class="row">
        <h1>Добавлен комментарий по цилиндрам заказа. Необходимо согласовать гравировку по указанной ссылке</h1>
    </div>
    <div class="row" style="display: inline-block;">
        <h5 style="margin-right: 15px">Заказ:</h5>
        <h5 style="margin-right: 15px">{{ $app->order_number }}</h5>
        <h5 style="margin-right: 15px">{{ $app->order_desc }}</h5>
    </div>
    <div class="row">
        <table>
            <thead>
                <tr>
                    <th style="border: 1px solid black">ID макро</th>
                    <th style="border: 1px solid black">Номер заказа</th>
                    <th style="border: 1px solid black">Описание заказа</th>
                    <th style="border: 1px solid black">Название клиента</th>
                    <th style="border: 1px solid black">Код клиента</th>
                    <th style="border: 1px solid black">МЦ</th>
                    <th style="border: 1px solid black">Плановый метраж</th>
                    <th style="border: 1px solid black">Фактический метраж</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid black">{{$app->macro_order_id}}</td>
                    <td style="border: 1px solid black">{{$app->order_number}}</td>
                    <td style="border: 1px solid black">{{$app->order_desc}}</td>
                    <td style="border: 1px solid black">{{$app->customer_desc}}</td>
                    <td style="border: 1px solid black">{{$app->customer_number}}</td>
                    <td style="border: 1px solid black">{{$app->machine_center}}</td>
                    <td style="border: 1px solid black">{{$app->planned_footage}}</td>
                    <td style="border: 1px solid black">{{$app->actual_footage}}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <a href="{{ $link }}" style="
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
            font-family: Arial, sans-serif;
        ">
            Перейти к согласованию
        </a>
    </div>
</div>

