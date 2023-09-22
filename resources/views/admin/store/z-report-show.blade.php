@extends('admin.layouts.index')

@section('content')
    <section class="one">
        <h5>{{$data['TaxPayerName']}}</h5>
        <h5>БИН {{$data['TaxPayerIN']}}</h5>
        <h5>НДС Серия {{$data['TaxPayerVATSeria']}} № {{$data['TaxPayerVATNumber']}}</h5>
    </section>

    <section class="two">
        <h5>СМЕННЫЙ Z-ОТЧЕТ</h5>

        <div>
            <p>Документ №{{$data['ReportNumber']}}</p>
            <p>Код кассира №{{$data['CashierCode']}}</p>
        </div>

        <h5>Смена №{{$data['ShiftNumber']}}</h5>
        <p>{{$data['StartOn']}} - {{$data['CloseOn']}}</p>
    </section>

    <section class="three">
        <h5>НЕОБНУЛЯЕМАЯ СУММА НА НАЧАЛО СМЕНЫ</h5>
        <div>
            <p>
                <span>Продажа</span>
                <span>{{$data['StartNonNullable']['Sell']}}</span>
            </p>
            <p>
                <span>Покупок</span>
                <span>{{$data['StartNonNullable']['Buy']}}</span>
            </p>
            <p>
                <span>Возврат продаж</span>
                <span>{{$data['StartNonNullable']['ReturnSell']}}</span>
            </p>
            <p>
                <span>Возврат продаж</span>
                <span>{{$data['StartNonNullable']['ReturnBuy']}}</span>
            </p>
        </div>
    </section>

    <section class="four">
        <p>
            <span>Продажа x 0</span>
            <span>{{$data['Sell']['Markup']}}</span>
        </p>
        <p>
            <span>Покупка x 0</span>
            <span>{{$data['Buy']['Markup']}}</span>
        </p>
        <p>
            <span>Возврат продажи x 0</span>
            <span>{{$data['ReturnSell']['Markup']}}</span>
        </p>
        <p>
            <span>возврат покупки x 0</span>
            <span>{{$data['ReturnBuy']['Markup']}}</span>
        </p>
    </section>


    <section class="five">
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
                <span>Возврат продаж</span>
                <span>{{$data['EndNonNullable']['ReturnBuy']}}</span>
            </p>
        </div>
    </section>

    <section class="six">
        <p>
            <span>Внесения</span>
            <span>{{$data['PutMoneySum']}}</span>
        </p>
        <p>
            <span>Изъятия</span>
            <span>{{$data['TakeMoneySum']}}</span>
        </p>
        <p>
            <span>Наличных в кассе</span>
            <span>{{$data['SumInCashbox']}}</span>
        </p>
        <p>
            <span>Контрольное значение</span>
            <span>{{$data['ControlSum']}}</span>
        </p>
    </section>

    <section class="seven">
        <p>Количество документов сформированных за смену: {{$data['DocumentCount']}}</p>
        <p>Сформирован оператором фискальных данных: АО "Казахтелеком"</p>
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

    </style>

@endsection



