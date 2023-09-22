@extends('admin.layouts.index')

@section('content-header-title','Заявка №'.$receipt->id)

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
                            <td>{{$receipt->id}}</td>
                        </tr>
                        <tr>
                            <th>Продавец</th>
                            <td>
                                <a href="{{route('admin.user.show',$receipt->user_id)}}">{{$receipt->user->name}}</a>
                            </td>
                        </tr>
                        @if($receipt->store->counteragent)
                            <tr>
                                <th>Контрагент</th>
                                <td>
                                    {{$receipt->store->counteragent->name}}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Торговый точка</th>
                            <td>{{$receipt->store->name}}
                            </td>
                        </tr>
                        <tr>
                            <th>Дата создании</th>
                            <td>{{$receipt->created_at}}</td>
                        </tr>
                        <tr>
                            <th>Сумма</th>
                            <td>{{$receipt->total_price}}</td>
                        </tr>
                        <tr>
                            <th>описание</th>
                            <td>{{$receipt->description}}</td>
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
                        @foreach($receipt->products()->get() as $basket)
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
