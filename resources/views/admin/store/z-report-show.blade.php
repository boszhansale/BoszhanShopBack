@extends('admin.layouts.index')

@section('content')

<section class="one">
    <h5>БИН {{$data['TaxPayerIN']}}</h5>
    <h5>НДС Серия {{$data['TaxPayerVATSeria']}} № {{$data['TaxPayerVATNumber']}}</h5>
</section>
<section class="two">
    <h5>СМЕННЫЙ Z-ОТЧЕТ</h5>

    <div>
        <p>Документ №{{$data['ReportNumber']}}</p>
    </div>
    <div>
        <p>Смену закрыл {{$data['CashierName']}}</p>
    </div>

    <h5>Смена №{{$data['ShiftNumber']}}</h5>
    <p>{{$data['StartOn']}} - {{$data['CloseOn']}}</p>
</section>
<section class="_sell">
    <table>
        <tr>
            <td> </td>
            <td>Количество</td>
            <td>Сумма</td>
        </tr>
        <tr>
            <td>Продажа</td>
            <td>{{$data['Sell']['Count']}}</td>
            <td>{{$data['Sell']['Taken'] - $data['Sell']['Change']}}</td>
        </tr>
        <tr>
            <td>Банковская карта</td>
            <td></td>
            <td>
                @foreach($data["Sell"]['PaymentsByTypesApiModel'] as $payments)
                    @if($payments['Type'] == 1){{$payments['Sum']}} @break @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Наличные</td>
            <td></td>
            <td>
                @foreach($data["Sell"]['PaymentsByTypesApiModel'] as $payments)
                    @if($payments['Type'] == 0){{$payments['Sum']}}@break @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Получено</td>
            <td></td>
            <td>{{$data['Sell']['Taken']}}</td>
        </tr>
        <tr>
            <td>Сдачи</td>
            <td></td>
            <td>{{$data['Sell']['Change']}}</td>
        </tr>
        <tr>
            <td>НДС</td>
            <td></td>
            <td>{{$data['Sell']['VAT']}}</td>
        </tr>
        <tr>
            <td>ПОКУПКА</td>
            <td>0</td>
            <td>{{$data['Buy']['Markup']}}</td>
        </tr>
        <tr>
            <td>ВОЗВРАТ ПРОДАЖ</td>
            <td>0</td>
            <td>{{$data['ReturnSell']['Markup']}}</td>
        </tr>
        <tr>
            <td>ПОЗВРАТ ПОКУПОК</td>
            <td>0</td>
            <td>{{$data['ReturnBuy']['Markup']}}</td>
        </tr>
        <tr>
            <td>Внесения</td>
            <td></td>
            <td>{{$data['PutMoneySum']}}</td>
        </tr>
        <tr>
            <td>Изъятия</td>
            <td></td>
            <td>{{$data['TakeMoneySum']}}</td>
        </tr>
        <tr>
            <td>НАЛИЧНЫХ В КАССЕ</td>
            <td></td>
            <td>{{$data['SumInCashbox']}}</td>
        </tr>

    </table>
</section>


<section class="three">
    <h5>НЕОБНУЛЯЕМАЯ СУММА НА КОНЕЦ СМЕНЫ</h5>
    <div>
        <p>
            <span>Продажа</span>
            <span>{{$data['EndNonNullable']['Sell']}}</span>
        </p>
        <p>
            <span>Покупок</span>
            <span>{{$data['EndNonNullable']['Buy']}}</span>
        </p>
        <p>
            <span>Возврат продаж</span>
            <span>{{$data['EndNonNullable']['ReturnSell']}}</span>
        </p>
        <p>
            <span>Возврат покупок</span>
            <span>{{$data['EndNonNullable']['ReturnBuy']}}</span>
        </p>
        <p>
            <span>Контрольное значение</span>
            <span>{{$data['ControlSum']}}</span>
        </p>
    </div>
</section>

<section class="seven">
    <p>Количество документов сформированных за смену: {{$data['DocumentCount']}}</p>
    <p>г. Алматы, Жетысуский район, ул. Дауылпаз,д.5/1</p>
    <p>Сформировано оператором фискальных данных: АО "Казахтелеком"</p>
</section>
<section class="end">
    <p>ИНК ОФД: {{$data['CashboxIN']}}</p>
    <p>Код ККМ КГД (РНМ): {{$data['CashboxRN']}}</p>
    <p>ЗНМ: {{$data['CashboxSN']}}</p>
</section>

<section class="end">
    <p>*** Конец отчета ***</p>
</section>


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
    section{
        border-bottom: 1px dotted #c3c3c3;
        padding: 12px 0;

    }
    h5{
        margin: 7px;
    }
    .one{
        text-align: center;
    }
    .one h5{
        margin: 5px 0;
    }
    .two{
        text-align: center;
    }
    .two div{
        display: flex;
        justify-content: space-between;
    }
    .three , .four ,.five{
        text-align: center;
    }
    .three div p,.four  p, .five div p , .six p{
        display: flex;
        justify-content: space-between;
    }
    ._sell table  {
        width: 100%;
    }
    .end{
        text-align: center;
    }

</style>



@endsection



