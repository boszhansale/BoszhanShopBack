@extends('admin.layouts.index')

@section('content-header-title',$counteragent->name)

@section('content')
    <div class="row">
        <div class="col">
            <a href="{{route('admin.order.index',['counteragent_id' => $counteragent->id])}}" class="btn btn-primary">заявки</a>
            <a href="{{route('admin.store.index',['counteragent_id' => $counteragent->id])}}" class="btn btn-primary">торговые
                точки</a>
            <a href="{{route('admin.counteragent.edit',$counteragent->id)}}" class="btn btn-warning">изменить</a>
            <a href="{{route('admin.counteragent.delete',$counteragent->id)}}" class="btn btn-danger">удалить</a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body ">
                    <table class="table table-bordered">
                        <tr>
                            <td>id_1c</td>
                            <td>{{$counteragent->id_1c}}</td>
                        </tr>
                        <tr>
                            <td>БИН</td>
                            <td>{{$counteragent->bin}}</td>
                        </tr>
                        <tr>
                            <td>ИИК</td>
                            <td>{{$counteragent->iik}}</td>
                        </tr>
                        <tr>
                            <td>БИК</td>
                            <td>{{$counteragent->bik}}</td>
                        </tr>
                        <tr>
                            <td>тип оплаты</td>
                            <td>{{$counteragent->paymentType->name}}</td>
                        </tr>
                        <tr>
                            <td>категория цен</td>
                            <td>{{$counteragent->pricetype->name}}</td>
                        </tr>
                        <tr>
                            <td>скидка</td>
                            <td>{{$counteragent->discount}}</td>
                        </tr>
                        <tr>
                            <td>активность</td>
                            <td>{{$counteragent->enabled}}</td>
                        </tr>
                        <tr>
                            <td>время доставки</td>
                            <td>{{$counteragent->delivery_time}}</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body ">
                    <table class="table table-bordered">
                        <tr>
                            <td>долг</td>
                            <td class="price">{{$counteragent->debt()}}</td>
                        </tr>
                        <tr>
                            <td>количество ТТ</td>
                            <td>{{$counteragent->stores()->count()}}</td>
                        </tr>
                        <tr>
                            <td>количество заявок</td>
                            <td>
                                <a href="{{route('admin.counteragent.order',$counteragent->id)}}">{{$counteragent->orders()->count()}}</a>
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
                <div class="card-header">
                    Торговые точки
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Название</th>
                            <th>Телефон</th>
                            <th>ТП</th>
                            <th>БИН</th>
                            <th>id_1c</th>
                            <th>скидка %</th>
                            <th>кол заявок</th>
                            <th>долг</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($counteragent->stores as $store)
                            <tr>
                                <th>{{$store->id}}</th>
                                <th>
                                    <b>{{$store->name}}</b><br>
                                    <small>{{$store->address}}</small>
                                </th>
                                <td>
                                    {{$store->phone}}
                                </td>
                                <td>
                                    {{$store->salesrep?->name}}
                                </td>


                                <td>
                                    {{$store->bin}}
                                </td>
                                <td>
                                    {{$store->id_sell}}
                                </td>

                                <td>
                                    {{$store->discount}}
                                </td>
                                <td>
                                    {{$store->orders()->count()}}
                                </td>
                                <td> {{$store->orders()->where('payment_status_id',2)->sum('purchase_price') - $store->orders()->where('payment_status_id',1)->sum('purchase_price')}}</td>


                                <td class="project-actions text-right">
                                    <a class="btn btn-primary btn-sm" href="{{route('admin.store.show',$store->id)}}">
                                        <i class="fas fa-folder">
                                        </i>
                                    </a>
                                    <a class="btn btn-info btn-sm" href="{{route('admin.store.edit',$store->id)}}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                    </a>
                                                                <a  class="btn btn-danger btn-sm" href="{{route('admin.store.delete',$store->id)}}" onclick="return confirm('Удалить?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>

{{--                                    <button class="btn btn-danger btn-sm" wire:click="delete({{$store->id}})"--}}
{{--                                            onclick="return confirm('Удалить?')">--}}
{{--                                        <i class="fas fa-trash"></i>--}}
{{--                                    </button>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
