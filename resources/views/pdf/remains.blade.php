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


<table>
    <thead>
    <tr>
        <th>№</th>
        <th>Код</th>
        <th>Артикул</th>
        <th>Название</th>
        <th>Ед.</th>
        <th>Остаток</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $product)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$product->id_1c}}</td>
            <td>{{$product->article}}</td>
            <td>{{$product->name}}</td>
            <td>{{$product->measure == 1 ? 'шт' : 'кг'}}</td>
            <td>{{$product->remains}}</td>
        </tr>
    @endforeach
    </tbody>
</table>






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
