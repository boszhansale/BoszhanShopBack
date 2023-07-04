@extends('admin.layouts.index')

@section('content-header-title','История')
@section('content')

    <div class="card mb-5">
        <div class="card-header"><h2>Корзина</h2></div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Продукты</th>
                    <th>оригинальные данные</th>
                    <th>изменение</th>
                </tr>
                </thead>
                <tbody>
                @foreach($refundProducer->baskets()->withTrashed()->get() as $basket)
                    <tr>
                        <td>{{$basket->product->name}}</td>

                        <td>
                            @foreach($basket->audits()->where('event','created')->get() as $audit)
                                <table style="margin-bottom: 5px">
                                    @foreach($audit->new_values as $key => $value)
                                        <tr>
                                            @switch($key)
                                                @case('count')
                                                    <td> количество</td>
                                                    <td>{{$value}}</td>
                                                    @break
                                                @case('all_price')
                                                    <td>цена</td>
                                                    <td>{{$value}}</td>
                                                    @break
                                                @default
                                                    @continue(2)
                                                    @break
                                            @endswitch
                                        </tr>
                                    @endforeach
                                </table>
                            @endforeach
                        </td>
                        <td>
                            @foreach($basket->audits()->where('event','updated')->get() as $audit)
                                <table style="margin-bottom: 5px">
                                    @foreach($audit->new_values as $key => $value)
                                        <tr>
                                            @switch($key)
                                                @case('count')
                                                    <td> количество</td>
                                                    <td>{{$value}}</td>
                                                    @break
                                                @case('all_price')
                                                    <td>цена</td>
                                                    <td>{{$value}}</td>
                                                    @break
                                                @default
                                                    @continue(2)
                                                    @break
                                            @endswitch
                                        </tr>
                                    @endforeach
                                </table>
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>


    @if(Auth::id() == 1)
        <div class="card">
            <h1>Видеть только супер АДМИН</h1>

            <div class="card-header">Заявка №{{$refundProducer->id}} от {{$refundProducer->created_at}}</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Дата изменения</th>
                        <th>Операция</th>
                        <th>Старый запись</th>
                        <th>новый запись</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($refundProducer->audits()->where('event','<>','created')->get() as $audit)
                        <tr>
                            <td>{{$audit->created_at}}</td>
                            <td>
                                @switch($audit->event)
                                    @case('created')
                                        создание заказа
                                        @break
                                    @case('updated')
                                        изменение
                                        @break
                                    @case('deleted')
                                        удален
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <th>ключ</th>
                                        <th>значение</th>
                                    </tr>
                                    @foreach($audit->old_values as $key => $value)
                                        <tr>
                                            <td>
                                                @switch($key)
                                                    @case('purchase_price')
                                                        сумма покупки
                                                        @break
                                                    @case('return_price')
                                                        сумма возврата
                                                        @break
                                                    @default
                                                        {{$key}}

                                                @endswitch
                                            </td>
                                            <td>{{$value}}</td>
                                        </tr>
                                    @endforeach
                                </table>

                            </td>
                            <td>
                                <table>
                                    <tr>
                                        <th>ключ</th>
                                        <th>значение</th>
                                    </tr>
                                    @foreach($audit->new_values as $key => $value)
                                        <tr>
                                            <td>
                                                @switch($key)
                                                    @case('purchase_price')
                                                        сумма покупки
                                                        @break
                                                    @case('return_price')
                                                        сумма возврата
                                                        @break
                                                    @default
                                                        {{$key}}

                                                @endswitch
                                            </td>
                                            <td>{{$value}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
