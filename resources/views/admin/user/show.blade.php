@extends('admin.layouts.index')

@section('content-header-title')
    <a href="{{route('admin.user.show',$user->id)}}">{{$user->name}}</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
                <a href="{{route('admin.user.edit',$user->id)}}" class="btn btn-warning">изменить</a>

                <a href="{{route('admin.order.index',['user_id' => $user->id])}}" class="btn btn-primary">продажи</a>
        </div>
    </div>
    <hr>
    <br>
    <div class="row">

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <td>{{$user->id}}</td>
                        </tr>
                        <tr>
                            <th>Логин</th>
                            <td>{{$user->login}}</td>
                        </tr>
                        <tr>
                            <th>Телефон номер</th>
                            <td>{{$user->phone}}</td>
                        </tr>
                        <tr>
                            <th>id_1c</th>
                            <td>{{$user->id_1c}}</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection



