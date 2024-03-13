@extends('admin.layouts.index')

@section('content-header-title','Заявка №'.$reject->id)

@section('content')

    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Обшая информация</div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>ID</th>
                            <td>{{$reject->id}}</td>
                        </tr>
                        <tr>
                            <th>Продавец</th>
                            <td>
                                <a href="{{route('admin.user.show',$reject->user_id)}}">{{$reject->user->name}}</a>
                            </td>
                        </tr>
                        @if($reject->store->counteragent)
                            <tr>
                                <th>Контрагент</th>
                                <td>
                                    <a href="{{route('admin.counteragent.show',$reject->store->counteragent_id)}}">{{$reject->store->counteragent->name}}</a>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Торговый точка</th>
                            <td><a href="{{route('admin.store.show',$reject->store_id)}}">{{$reject->store->name}}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Описания</th>
                            <td>{{$reject->description}}</td>
                        </tr>

                        <tr>
                            <th>Дата создании</th>
                            <td>{{$reject->created_at}}</td>
                        </tr>
                        <tr>
                            <th>Сумма</th>
                            <td><span class="price">{{$reject->total_price}}</span></td>
                        </tr>

                        @if($reject->inventory)
                            <tr>
                                <th>Инвентаризация</th>
                                <td>
                                    <a href="{{route('admin.inventory.show',$reject->inventory_id)}}">
                                        {{$reject->inventory->id}} от {{$reject->inventory->created_at}}
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Продукт</th>
                            <th>артикул</th>
                            <th>шт/кг</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>итог</th>
                            <th>коммент</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reject->products()->get() as $basket)
                            <tr>
                                <td>{{$basket->product->id}}</td>
                                <td>{{$basket->product->name}}</td>
                                <td>{{$basket->product->article}}</td>
                                <td>{{$basket->product->measureDescription()}}</td>
                                <td>{{$basket->price}}</td>
                                <td>{{$basket->count}}</td>
                                <td>{{$basket->all_price}}</td>
                                <td>{{$basket->comment}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
