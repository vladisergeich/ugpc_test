<!DOCTYPE html>
<html>

<head>
    <title>Invoice Example</title>
    <meta charset="win-1251">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: DejaVu Sans
        }
    </style>
</head>

<body style="font-size: 16px">
    <div style="width: 100%; max-width: 1300px;  margin: auto">

        <table class="iksweb"
            style="width: 100%;
        border-spacing:0;
        height: auto;
        font-size: 13px;">
            <tbody>
                <tr>
                    <td style="width: 80px; text-align: left; align-items: top; vertical-align:top;">
                        <div class="" style="display: block">
                            <img width="250" height="50" alt=""
                                src="data:image/jpeg;base64,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
                            wBmDw8bjRbi8TxDp5tNXsdPk8OX+8tJAqXE0e6LcNyhQVCSeWRhef1PsPi38QNJ8B/CO6uvDur6rrniPQVOuWUentCsWpNawMBcyeXizjErTbiwGApUKz7UMOs+JPiloPwW8cbpbzVPFem+ILe30yW00kQm9tX+wvMlqpilQD97dRRSzB0QqpmfakjjrXEmY/W3iW4e8uXl15bXttfvre/Xtocv+r2X/VVh0pe6+bm05r2vvbtpa3T5ncfsw+MfHHjv4GeH9R+JPhE+CPHL2wj1jSlvbe7ijuF+VpIpIJJEMb43qC25Q2DyMnvq8m+AVz8QLb4k+KNO8YPqs+lw6Xpl1ps141rMPPklvluUWa3tbdGKrFbEphiu4HOHFes18jiVaq9u+m2utlfsfVYaV6S37a76aahRRRWBuFFFFABXj37T/wC3l8Lv2Q9P/wCKy8T2serygfZdCsf9L1a+ZvurHbId/wAxwAzbUyeWFVPjn+yy3xD/ALW1TUPGvxj1W0bdMnhrQPEEGkRSrj/j3jaIW7EH/prcc55avnL4ffBHx/4BvpP+FFfsweCvhVfXJOfGPxC1qHUNTVW+83l28lxcbup+eYgnqpFbQhF6t/p/X3H3nDuQZTiF7bF1+a1rx5qdJfOdSXN/4BRm30OO+Pdz46/bj8NSeLvj9fyfs7/szaTItyPDt3c+Tr3iwqd0a3AxuTdjIiC7sgbUdtso5aLT7X9urwzpHivxfpkPwZ/Ym+Ew+06To9yTazeLWiysbui8lGJwNuSxdlQySOzrv+Mvgr4Z0L4kQeKPixqPj39sP4s25K6b4c0LRHbw1pD9wVRTaxoDgMZG56mEsMjfh/4J5/GX/gov490rxB+0ffWngX4caJMJdJ+GugXIbaijCieaM7FJXALgs+CyqIM8dfNFLey/rZbt+bP2SlmGX4HDwnKtHD0aafLKKceS6tL6tSn+9q1prR4qsoqP2UrIZ+wUmt/8FJ/2w0+PesaLP4e+EXwwhl0b4a6K6CON5SPLkudi/LlUHO0bQxiRWPkGv0YrL8FeCtI+G/hLTtB0HTrPSNG0mBbazsrWIRw20ajAVVHAFalcVSfM9Nj8G4s4hjm2NVShT9nQpxUKUN+WCva76yk25TfWUmwooorM+YCiiigCO8s4tQtJYJ4o54J0MckcihkkUjBUg8EEdRUVxo1pd20MMtrbSQ2zpJDG8SlYmQgoyjGAVIBBHTAxRRRdgWaKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigAooooAKKKKACiiigD//Z">
                        </div>

                        <div class=""
                            style="border: 1px solid black; align-items: top; padding: 10px; width: 90%; margin-bottom: 15px; margin-top: 15px;">
                            <div style="display: block;">
                                <label style="font-weight: bold;"> Дата отгрузки:</label>
                                <span>{{ date('d.m.Y', strtotime($shaftrequest->shipping_date)) }}</span>
                            </div>
                            <div style="display: block;">
                                <label style="font-weight: bold;"> Отправитель:</label>
                                <span>{{ $shaftrequest->sender }}</span>
                            </div>
                            <div style="display: block;">
                                <label style="font-weight: bold;">Получатель:</label>
                                <span>{{ $shaftrequest->recipient }}</span>
                            </div>
                        </div>
                        <div class=""
                            style="border: 1px solid black; align-items: top; padding: 10px; width: 90%; margin-bottom: 15px; ">
                            <label style="font-weight: bold;">Комментарий:</label>
                            <div style="display: block;">
                                <span>{{ $shaftrequest->comment }}</span>
                            </div>
                        </div>
                        <div class=""
                            style="border: 1px solid black; align-items: top; padding: 10px; margin-top: 20px; width: 90%">
                            <div style="display: block;">
                                <label style="font-weight: bold;"> Адрес отправителя:</label>
                                <span>{{ $sender->address }}</span>
                            </div>
                            <div style="display: block;">
                                <label style="font-weight: bold;"> тел/факс:</label>
                                <span>{{ $sender->phone_fax }}</span>
                            </div>
                        </div>
                        <div class=""
                            style="border: 1px solid black; align-items: top; padding: 10px; margin-top: 20px; width: 90%">
                            <div style="display: block;">
                                <label style="font-weight: bold;"> Контакт отправителя:</label>
                                <span>{{ $sender->contact }}</span>
                            </div>
                            <div style="display: block;">
                                <label style="font-weight: bold;"> Должность:</label>
                                <span>{{ $sender->contact_position }}</span>
                            </div>
                            <div style="display: block;">
                                <label style="font-weight: bold;"> тел:</label>
                                <span>{{ $sender->contact_phone }}</span>
                                <span>{{ $sender->contact_two }}</span>
                            </div>
                        </div>
                    <td style="width: 480px; text-align: left; vertical-align:top;">
                        <div style="display: block; align-items: top; margin-bottom: 15px;">
                            <label style="font-weight: bold; text-align: center;">В отдел логистики ГК ДАНАФЛЕКС</label>
                        </div>
                        <div class="" style="display: inline-block; align-items: top;">
                            <label style="font-weight: bold; text-align: 16px;">Заявка на перемещение валов №
                                {{ $shaftrequest->document_number }}</label>
                        </div>
                        <div class="" style="display: inline-block; margin-left: 30px; align-items: top;">
                            <label style="font-weight: bold;">от {{ date('d.m.Y', strtotime($shaftrequest->document_date)) }}</label>
                        </div>
                        <div class=""
                            style="border: 1px solid black; align-items: top; padding: 10px; margin-top: 20px; width: 90%">
                            <div style="display: block;">
                                <label style="font-weight: bold;"> Адрес получателя:</label>
                                <span>{{ $recipient->address }}</span>
                            </div>
                            <div style="display: block;">
                                <label style="font-weight: bold;"> тел/факс:</label>
                                <span>{{ $recipient->phone_fax }}</span>
                            </div>
                        </div>
                        <div class=""
                            style="border: 1px solid black; align-items: top; padding: 10px; margin-top: 20px; width: 90%">
                            <div style="display: block;">
                                <label style="font-weight: bold;"> Контакт получателя:</label>
                                <span>{{ $recipient->contact }}</span>
                            </div>
                            <div style="display: block;">
                                <label style="font-weight: bold;"> Контакт:</label>
                                <span>{{ $recipient->contact_position }}</span>
                            </div>
                            <div style="display: block;">
                                <label style="font-weight: bold;"> тел:</label>
                                <span>{{ $recipient->contact_phone }}</span>
                            </div>
                        </div>
                        <div class=""
                            style="border: 1px solid black; align-items: top; padding: 10px; margin-top: 20px; width: 90%">
                            <div style="display: block;">
                                <label style="font-weight: bold;"> Заявку заполнил:</label>
                                <span>{{ $shaftrequest->request_creator }}</span>
                            </div>
                        </div>
                        <div class=""
                            style="border: 1px solid black; align-items: top; padding: 10px; margin-top: 20px; width: 90%">
                            <div style="display: inline-block;">
                                <label style="font-weight: bold;"> Итого валов:</label>
                                <span>{{ $shaftrequestcomp->count() }}</span>
                            </div>
                            <div style="display: inline-block;">
                                <label style="font-weight: bold;"> Кол-во поддонов:</label>
                                <span>{{ $poddon_count }}</span>
                            </div>
                            <div style="display: inline-block;">
                                <label style="font-weight: bold;"> Масса*:</label>
                                <span>{{ $shaftrequestcomp->count() * 160 }}</span>
                                <span>КГ</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="text-align: left; font-size: 12px; width: 95%;">
            <thead>
                <tr>
                    <th scope="col">Оквид</th>
                    <th scope="col">Пред. Оквид</th>
                    <th scope="col">Партия</th>
                    <th scope="col">ID вала</th>
                    <th scope="col">FF</th>
                    <th scope="col">Ячейка</th>
                    <th scope="col">Назначение</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shafts as $composition)
                    <tr>
                        <td style="border: 1px solid; height: 10px; padding: 5px;">
                            {{ substr($composition[0]->okvid_number, 0, strlen($composition[0]->okvid_number) - 2) . '-' . substr($composition[0]->okvid_number, -2) }}
                        </td>
                        <td style="border: 1px solid; height: 10px; padding: 5px; width: 60px;">
                            @if ($composition[3] != '')
                                {{ substr($composition[3]->okvid_number ?? '', 0, strlen($composition[3]->okvid_number ?? '') - 2) . '-' . substr($composition[3]->okvid_number ?? '', -2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="border: 1px solid; height: 10px; padding: 5px;">{{ $composition[2]->prod_order ?? ''}}
                        </td>
                        <td style="border: 1px solid; height: 10px; padding: 5px; width: 60px;">
                            {{ $composition[0]->shaft_id }}</td>
                        <td style="border: 1px solid; height: 10px; padding: 5px;">{{ $composition[0]->ff }}</td>
                        <td style="border: 1px solid; height: 10px; padding: 5px;">
                            {{ $composition[1]->warehouse_place ?? ''}}</td>
                        <td style="border: 1px solid; height: 10px; padding: 5px;">{{ $composition[0]->destination }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>

<style>
    @page {
        margin: 10px;
    }
</style>
