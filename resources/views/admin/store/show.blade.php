@extends('admin.layouts.index')

@section('content-header-title',$store->name)

@section('content')
    <div class="row">
        <div class="col-md-12">

            <a class="btn btn-info btn-sm" href="{{route('admin.store.edit',$store->id)}}">
                изменить
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



