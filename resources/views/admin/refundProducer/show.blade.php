@extends('admin.layouts.index')

@section('content-header-title','Заявка №'.$refundProducer->id)

@section('content')

    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Обшая информация</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td>{{$refundProducer->id}}</td>
                        </tr>
                        <tr>
                            <th>Продавец</th>
                            <td>
                                <a href="{{route('admin.user.show',$refundProducer->user_id)}}">{{$refundProducer->user->name}}</a>
                            </td>
                        </tr>
                        @if($refundProducer->store->counteragent)
                            <tr>
                                <th>Контрагент</th>
                                <td>
                                    <a href="{{route('admin.counteragent.show',$refundProducer->store->counteragent_id)}}">{{$refundProducer->store->counteragent->name}}</a>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Торговый точка</th>
                            <td><a href="{{route('admin.store.show',$refundProducer->store_id)}}">{{$refundProducer->store->name}}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Тип оплаты</th>
                            <td>{{$refundProducer->payment_type}}</td>
                        </tr>

                        <tr>
                            <th>Дата создании</th>
                            <td>{{$refundProducer->created_at}}</td>
                        </tr>
                        <tr>
                            <th>Сумма</th>
                            <td>{{$refundProducer->total_price}}</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Покупки на сумму {{$refundProducer->products()->sum('all_price')}}</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Продукт</th>
                            <th>артикул</th>
                            <th>шт/кг</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>итог</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($refundProducer->products()->get() as $basket)
                            <tr>
                                <td>{{$basket->product->id}}</td>
                                <td>{{$basket->product->name}}</td>
                                <td>{{$basket->product->article}}</td>
                                <td>{{$basket->product->measureDescription()}}</td>
                                <td>{{$basket->price}}</td>
                                <td>{{$basket->count}}</td>
                                <td>{{$basket->all_price}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
