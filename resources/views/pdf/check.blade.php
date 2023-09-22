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

    @foreach($data['Lines'] as $item)
        <p>{{$item['Value']}}</p>
    @endforeach

<style>
    body { font-family: DejaVu Sans, sans-serif; }
    p {
        font-size: 16px;
        margin: 0;
    }
    @media print {
        @page {
            /*size: 80mm 80mm; !* Ширина и высота бумаги в миллиметрах *!*/
            margin: 0; /* Установите нужные поля для страницы */
        }
    }
</style>

</body>
</html>
