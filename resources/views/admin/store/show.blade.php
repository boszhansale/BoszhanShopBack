@extends('admin.layouts.index')

@section('content-header-title',$store->name)

@section('content')
    <div class="row">
        <div class="col-md-12">

            <a class="btn btn-info btn-sm" href="{{route('admin.store.edit',$store->id)}}">
                изменить
            </a>

            <a class="btn btn-info btn-sm" href="{{route('admin.discountCard.index',$store->id)}}">
                дисконт карты
            </a>

            <a class="btn btn-info btn-sm" href="{{route('admin.storeProductDiscount.index',$store->id)}}">
                скидки
            </a>
            <a class="btn btn-info btn-sm" href="{{route('admin.storeProductPromotion.index',$store->id)}}">
                акции
            </a>
        </div>
        <br>
        <br>
        <div class="col-md-12">
            <a class="btn btn-warning btn-sm" href="{{route('admin.store.z-report',$store->id)}}">
                Z отчеты
            </a>
            <a class="btn btn-warning btn-sm" href="{{route('admin.report.remain',$store->id)}}">
                Остатки товаров
            </a>
            <a class="btn btn-warning btn-sm" href="{{route('admin.report.discount-card',$store->id)}}">
                Продажи по дисконтным картам
            </a>
            <a class="btn btn-warning btn-sm" href="{{route('admin.report.order',$store->id)}}">
                Продажи
            </a>
            <a class="btn btn-warning btn-sm" href="{{route('admin.report.product',$store->id)}}">
                Товарный отчет в разрезе
            </a>
            <a class="btn btn-warning btn-sm" href="{{route('admin.report.inventory',$store->id)}}">
                Инвентаризация
            </a>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <table class="table  table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>{{$store->id}}</td>
                        </tr>
                        <tr>
                            <td>Адрес</td>
                            <td>{{$store->address}}</td>
                        </tr>
                        <tr>
                            <td>Телефон номер</td>
                            <td>{{$store->phone}}</td>
                        </tr>
                        <tr>
                            <td>БИН</td>
                            <td>{{$store->bin}}</td>
                        </tr>
                        <tr>
                            <td>id_sell</td>
                            <td>{{$store->id_sell}}</td>
                        </tr>
                        <tr>
                            <td>Дата создание</td>
                            <td>{{$store->created_at}}</td>
                        </tr>
                        <tr>
                            <td>Контрагент</td>
                            <td>{{$store->counteragent?->name}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection



