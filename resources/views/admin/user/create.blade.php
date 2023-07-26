@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.user.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div>{{$error}}</div>
            @endforeach
        @endif
        <div class="row">
            <div class="col-md-6">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div>{{$error}}</div>
                    @endforeach
                @endif
                <div class="form-group">
                    <label for="">ФИО</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="form-group">
                    <label for="">Логин</label>
                    <input type="text" class="form-control" name="login" required >
                </div>

                <div class="form-group">
                    <label for="">Телефон номер</label>
                    <input type="text" class="form-control" name="phone">
                </div>

                <div class="form-group">
                    <label for="">Новый Пароль</label>
                    <input type="text" class="form-control" name="password" required>
                </div>

                <div class="form-group">
                    <label for="">id_1c</label>
                    <input type="text" class="form-control" name="id_1c">
                </div>
                <div class="form-group">
                    <label for="">магазин</label>
                    <select name="store_id" class="form-control">
                        @foreach($stores as $store)
                            <option value="{{$store->id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">склад</label>
                    <select name="storage_id" class="form-control">
                        @foreach($storages as $storage)
                            <option value="{{$storage->id}}" >{{$storage->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">

                <div class="form-group">
                    <label for="">webkassa_login</label>
                    <input type="text" class="form-control" name="webkassa_login" required >
                </div>
                <div class="form-group">
                    <label for="">webkassa_password</label>
                    <input type="text" class="form-control" name="webkassa_password" required >
                </div>

                <div class="form-group">
                    <label for="">webkassa_cash_box_id</label>
                    <input type="text" class="form-control" name="webkassa_cash_box_id" >
                </div>

            </div>
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
