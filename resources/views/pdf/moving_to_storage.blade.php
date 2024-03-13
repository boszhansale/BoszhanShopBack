<!doctype html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ТОО "Первомайские Деликатесы"</title>
    <script>
        // window.onload = function() {
        //     window.print();
        // };
    </script>
</head>
<body>
<h3>Акт приема-передачи продукции № {{$moving->id}} от {{\Carbon\Carbon::parse($moving->created_at)->format('d.m.Y')}}</h3>
<h5>ТОО "Первомайские Деликатесы" БИН130740008859 отпустил, в лице {{$moving->user->name}} с одной стороны, и</h5>
<h5>Материалы ответственное лицо Белялов К.М. принял с другой стороны, в связи с чем Стороны подписали настоящий Акт приема-передачи следующей продукции </h5>

<h2>Примечание: {{$moving->store->name}}</h2>
<table style="width: 100%">
    <thead>
    <tr>
        <th>№</th>
        <th>Код</th>
        <th>Артикул</th>
        <th>Штрихкод</th>
        <th>Наименование товара</th>
        <th>Ед</th>
        <th>Кол-во</th>
        <th>Цена KZT</th>
        <th>Сумма KZT</th>
    </tr>
    </thead>
    <tbody>
    @foreach($moving->products as $product)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$product->product->id_1c}}</td>
            <td>{{$product->product->article}}</td>
            <td>{{$product->product->barcode}}</td>
            <td>{{$product->product->name}}</td>
            <td>{{$product->product->measureDescription()}}</td>
            <td>{{$product->count}}</td>
            <td>{{$product->price}}</td>
            <td>{{$product->all_price}}</td>
        </tr>
    @endforeach

    <tr>
        <th colspan="6">Всего отпущено:</th>
        <td>{{$moving->products()->sum('count')}}</td>
        <td></td>
        <td>{{$moving->products()->sum('all_price')}}</td>
    </tr>
    </tbody>
</table>
<br>
<br>
<table style="width: 100%">
    <tr>
        <td>Отпустил: Продавец</td>
        <td>Принял на возврат: Экспедитор</td>
        <td>Принял: Кладовщик возвратного склада</td>
    </tr>
    <tr style="height: 75px">
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>(Ф.И.О., печать, роспись)</td>
        <td>(Ф.И.О., печать, роспись)</td>
        <td>(Ф.И.О., печать, роспись)</td>
    </tr>
</table>



<style>
    body { font-family: DejaVu Sans, sans-serif; }
    h3{
        text-align: center;
    }
    table, tr, td,th{
        border-collapse: collapse;
        border: 1px solid black;
        padding: 4px;
    }
    div{
        display: flex;
        justify-content: space-between;
    }
</style>

</body>
</html>
