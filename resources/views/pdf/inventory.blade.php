<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>ТОО "Первомайские Деликатесы"</title>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body>


<section class="one">
    <p>Приложения 43</p>
    <p>к приказу Министра финансов Республики Казахстан</p>
    <p>от 21 июня 2007 года № 216</p>
</section>

<section class="two">
    <div>
        Организация (индивидуальный предприниматель) ТОО "Первомайские Деликатесы"
    </div>
    <div>
        <div>
            <p>Форма Инв-4</p>
            <table>
                <tr>
                    <td></td>
                    <th>Коды</th>
                </tr>
                <tr>
                    <td>ОКПО</td>
                    <td>52164099</td>
                </tr>
                <tr>
                    <td>РНН</td>
                    <td>090400231378</td>
                </tr>
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <th>Номер документа</th>
                    <th>Дата составления</th>
                </tr>
                <tr>
                    <td>{{$inventory->id}}</td>
                    <td>{{\Carbon\Carbon::parse($inventory->created_at)->format('d.m.Y')}}</td>
                </tr>
            </table>
        </div>
    </div>
</section>


<section class="three">
    <h4>ИНВЕНТАРИЗАЦИОННАЯ ОПИСЬ ЗАПАСОВ</h4>
    <p>Номенклатура</p>
    <p>вид запасов</p>
    <h6>РАСПИСКА</h6>
    <span>
        К началу проведения инвентаризации все доукменты, относящиеся к приходу и расходу запасов, сданы в бухгалтерию, и все запасы, поступившие на мою ответственность,оприходованы, а выбывшие списаны в расход.
    </span>
</section>

<section class="four">
    <p>Склад</p>
    <p>Участок термической обработки колбасной продукции Алматы (Р) (ГО)</p>
    <p>Материально-ответственное (ые) лицо (а)</p>

    <div>
        <div>
            <p>Аппаратчик термической обработки мясопродуктов</p>
            <span>должность</span>
        </div>
        <div>
            <p>________________</p>
            <span>подпись</span>
        </div>
        <div>
            <p>	Рудниченко Сергей Сергеевич</p>
            <span>расшифровка подписи</span>

        </div>
    </div>
</section>



<section class="six">
    <p>На основании приказа (распоряжения) от "_______" ________________________ 20_______ года №_________ произведено снятие фактических остатков_______________________
    </p>
    <p>числящихся на балансовых счетах № ___________________________ по состоянию на "______"_________________________20_____года
    </p>
    <p>Инвентаризация начата "{{\Carbon\Carbon::parse($inventory->created_at)->format('d')}}"  {{\App\Services\NumWord::month(\Carbon\Carbon::parse($inventory->created_at)->format('m'))}} {{\Carbon\Carbon::parse($inventory->created_at)->format('Y')}} года</p>
    <p>Инвентаризация окончена "{{\Carbon\Carbon::parse($inventory->created_at)->format('d')}}"  {{\App\Services\NumWord::month(\Carbon\Carbon::parse($inventory->created_at)->format('m'))}} {{\Carbon\Carbon::parse($inventory->created_at)->format('Y')}} года</p>
</section>

<section class="seven">
    <p>При инвентаризации установлено следующее:</p>

    <table>
        <thead>
        <tr>
            <td colspan="6"></td>
            <th colspan="2">Фактическое наличие</th>
            <th colspan="2" >По данным бухгалтерского учета</th>
        </tr>
        <tr>
            <th>Номер по порядку</th>
            <th>Запасы (наименование,  краткая характеристика)</th>
            <th>Балансовый счет</th>
            <th>Номенклатурный номер</th>
            <th>Единица измерения</th>
            <th>Цена, в тенге</th>
            <th>количество</th>
            <th>сумма, в тенге</th>
            <th>количество</th>
            <th>сумма, в тенге</th>
        </tr>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
            <td>6</td>
            <td>7</td>
            <td>8</td>
            <td>9</td>
            <td>10</td>
        </tr>
        @foreach($inventory->products as $product)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$product->product->name}}</td>
                <td></td>
                <td>{{$product->product->article}}</td>
                <td>{{$product->product->measureDescription()}}</td>
                <td>{{$product->price}}</td>
                <td>{{$product->count}}</td>
                <td>{{$product->count * $product->price}}</td>
                <td>{{$product->receipt}}</td>
                <td>{{$product->receipt * $product->price}}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="6">Итого:</th>
            <th>{{number_format($inventory->products()->sum('count'),2,'.','')   }}</th>
            <th>{{number_format($inventory->products()->sum(DB::raw('count * price')),2,'.','')   }}</th>
            <th>{{number_format($inventory->products()->sum('receipt'),2,'.','')}}</th>
            <th>{{number_format($inventory->products()->sum(DB::raw('receipt * price')), 2, '.', '')}}</th>
        </tr>
        </thead>

    </table>


</section>

<section class="eight">
    <p>Итого по описи:</p>

    <div>
        <div>
            <p>а) порядковых номеров: <b>@numeral($inventory->products()->count())</b></p>
        </div>
        <div>
            <p>б) общее количество, фактически: <b>@numeral(round($inventory->products()->sum('count')))</b></p>

        </div>
        <div>
            <p>в) на сумму, в тенге, фактически: <b>{{\App\Services\NumWord::amount($inventory->products()->sum(DB::raw('count * price')))}}</b></p>

        </div>
    </div>

</section>

<section class="nine">
    <div>
        <p>Председатель комиссии</p>
        <div>
            <div>
                <p>Генеральный директор</p>
                <span>должность</span>
            </div>

            <div>
                <p></p>
                <span>подпись</span>
            </div>
            <div>
                <p>Бажанов Нуржан Кадыркулович</p>
                <span>расшифровка подписи</span>
            </div>
        </div>
    </div>

    <div>
        <p>Члены комиссии</p>
        <div>
            <div>
                <p>Главный технолог</p>
                <span>должность</span>
            </div>

            <div>
                <p></p>
                <span>подпись</span>
            </div>
            <div>
                <p>Рудик Ольга</p>
                <span>расшифровка подписи</span>
            </div>
        </div>
        <div>
            <div>
                <p>Бухгалтер</p>
                <span>должность</span>
            </div>

            <div>
                <p></p>
                <span>подпись</span>
            </div>
            <div>
                <p>Кырыкбаева Гульнара Назымбековна</p>
                <span>расшифровка подписи</span>
            </div>
        </div>
    </div>
</section>
<section>
    Все запасы, поименованные в настоящей инвентаризационной описи с №________по №___________, комиссией проверены в натуре в моем (нашем) присутствии и внесены
    в опись, в связи с чем претензий к инвентаризационной комиссии не имею (не имеем). Запасы, перечисленные в описи, находятся на моем (нашем) ответственном хранении.
    Материально-ответственное (ые) лицо (а) за сохранность запасов "_________"__________________ 20 __________ года

</section>

<style>
    @media print{@page {size: landscape}}

    body { font-family: DejaVu Sans, sans-serif; }
    .one{
        text-align: right;
    }
    .two{
        display: flex;
        justify-content: space-between;
    }
    .two div:nth-child(2){
        text-align: right;
        display: flex;
        flex-direction: column;
        align-items: end;
    }
    .two div:nth-child(2) table{
        margin-bottom: 25px;
    }
    .two div:nth-child(2) table, th, td{
        border-collapse: collapse;
        border: 1px solid black;
    }
    .three{
        text-align: center;
    }
    .four div{
        display: flex;
    }
    .four div p {
        min-width: 100px;
        margin-right: 10px;
    }
    .four div div{
        position: relative;
    }
    .four div span{
        position: absolute;
        bottom: 0;
        font-size: 12px;
        font-style: italic;
    }

    .seven table{
        border-collapse: collapse;
        border: 1px solid black;
    }

    .eight{
        display: flex;
        margin-top: 15px;
    }
    .eight p{
        margin-right: 20px;
    }
    .eight > div > div{
        position: relative;
    }
    .eight span{
        position: absolute;
        bottom: -12px;
        left: 49%;
        font-style: italic;
        font-size: 12px;
    }
    .nine{
        display: flex;
        flex-direction: column;
        margin: 15px 0;
    }
    .nine > div > p{
        font-weight: bold;
    }
    .nine > div{
        display: flex;
        flex-direction: column;
    }
    .nine > div > div{
        margin-left: 30px;
        display: flex;

    }
    .nine > div > div > div{
        position: relative;
        margin-right: 40px;
    }
    .nine > div > div  p{
        border-bottom: 1px solid black;
        min-width: 172px;
        height: 20px;
    }
    .nine span{
        position: absolute;
        bottom: 0px;
        font-size: 12px;
        font-style: italic;
        left: 0;
    }
</style>

</body>
</html>
