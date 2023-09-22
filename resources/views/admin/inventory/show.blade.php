@extends('admin.layouts.index')

@section('content-header-title','Заявка №'.$inventory->id)

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
                            <td>{{$inventory->id}}</td>
                        </tr>
                        <tr>
                            <th>Продавец</th>
                            <td>
                                <a href="{{route('admin.user.show',$inventory->user_id)}}">{{$inventory->user->name}}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Торговый точка</th>
                            <td><a href="{{route('admin.store.show',$inventory->store_id)}}">{{$inventory->store->name}}</a>
                            </td>
                        </tr>

                        <tr>
                            <th>Дата создании</th>
                            <td>{{$inventory->created_at}}</td>
                        </tr>
                        @foreach($rejects as $reject)
                            <tr>
                                <th>Недостача</th>
                                <td><a href="{{route('admin.reject.show',$reject->id)}}">#{{$reject->id}}</a></td>
                            </tr>
                        @endforeach
                        @foreach($receipts as $receipt)
                            <tr>
                                <th>Излишки</th>
                                <td><a href="{{route('admin.receipt.show',$receipt->id)}}">#{{$receipt->id}}</a></td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Продукт</th>
                            <th>артикул</th>
                            <th>шт/кг</th>
                            <th>Цена</th>

                            <th>факт кол</th>
                            <th>поступления</th>
                            <th>продажа</th>
                            <th>Недостача</th>
                            <th>излишки</th>
                            <th>с склада</th>
                            <th>на склада</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($inventory->products()->get() as $basket)
                            <tr>
                                <td>
                                    <a href="{{route('admin.product.show',$basket->product_id)}}">
                                        {{$basket->product->name}}
                                    </a>
                                </td>
                                <td>{{$basket->product->article}}</td>
                                <td>{{$basket->product->measureDescription()}}</td>
                                <td>{{$basket->price}}</td>
                                <td>{{$basket->count}}</td>
                                <td>{{$basket->receipt}}</td>
                                <td>{{$basket->sale}}</td>
                                <td>{{$basket->shortage}}</td>
                                <td>{{$basket->overage}}</td>
                                <td>{{$basket->moving_from}}</td>
                                <td>{{$basket->moving_to}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
