<!doctype html>
<html lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ТОО "Первомайские Деликатесы"</title>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body>
<h3>ТОО "Первомайские Деликатесы"</h3>

<h2>Накладная перемещения № {{$moving->id}} от {{$moving->created_at->format('d.m.Y')}} г.</h2>

<h4>Склад-отправитель: Товар в пути</h4>
<h4>Склад-получатель: Центральный склад специй и оболочек и упаковочных материалов (ГО) Бакиева Ж.А.</h4>
<p>Примечание: Интериндустрия ООО</p>

<table>
    <thead>
    <tr>
        <th>№</th>
        <th>Наименование товара</th>
        <th>Ед</th>
        <th>Кол-во</th>
        <th>Цена KZT</th>
        <th>Сумма KZT</th>
        <th>Вес, кг.</th>
        <th>Фактически принято</th>
    </tr>
    </thead>
    <tbody>
    @foreach($moving->products as $product)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$product->product->name}}</td>
            <td>{{$product->product->measureDescription()}}</td>
            <td>{{$product->count}}</td>
            <td>{{$product->price}}</td>
            <td>{{$product->all_price}}</td>
            <td>{{$product->count}}</td>
            <td> </td>
        </tr>
    @endforeach

    <tr>
        <th colspan="3">Всего отпущено:</th>
        <td>{{$moving->products()->sum('count')}}</td>
        <td></td>
        <td>{{$moving->products()->sum('all_price')}}</td>
        <td>{{$moving->products()->sum('count')}}</td>
        <td></td>
    </tr>
    </tbody>
</table>


<p>Отгружено на сумму: {{\App\Services\NumWord::amount($moving->products()->sum('all_price'))}}</p>


<div>
    <p>Отпустил______________</p>
    <p>Получил______________Бакиева Ж.А.</p>
</div>


<style>
    body { font-family: DejaVu Sans, sans-serif; }
    h2{
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
