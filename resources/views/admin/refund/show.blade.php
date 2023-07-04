@extends('admin.layouts.index')

@section('content-header-title','Заявка №'.$refund->id)

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
                            <td>{{$refund->id}}</td>
                        </tr>
                        <tr>
                            <th>Продавец</th>
                            <td>
                                <a href="{{route('admin.user.show',$refund->user_id)}}">{{$refund->user->name}}</a>
                            </td>
                        </tr>
                        @if($refund->store->counteragent)
                            <tr>
                                <th>Контрагент</th>
                                <td>
                                    <a href="{{route('admin.counteragent.show',$refund->store->counteragent_id)}}">{{$refund->store->counteragent->name}}</a>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Торговый точка</th>
                            <td><a href="{{route('admin.store.show',$refund->store_id)}}">{{$refund->store->name}}</a>
                            </td>
                        </tr>

                        <tr>
                            <th>Дата создании</th>
                            <td>{{$refund->created_at}}</td>
                        </tr>
                        <tr>
                            <th>Сумма</th>
                            <td>{{$refund->total_price}}</td>
                        </tr>
                        <tr>
                            <th>продажа</th>
                            <td>
                                <a target="_blank" href="{{route('admin.order.show',$refund->order_id)}}">{{$refund->order_id}}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>чек</th>
                            <td>
                                @if($refund->ticket_print_url)
                                    <a target="_blank" href="{{$refund->ticket_print_url}}">{{$refund->check_number}}</a>
                                @endif
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
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
                        @foreach($refund->products()->get() as $basket)
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
