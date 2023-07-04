@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.user.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">ФИО</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="form-group">
                    <label for="">Логин</label>
                    <input type="text" class="form-control" name="login" required>
                </div>

                <div class="form-group">
                    <label for="">Телефон номер</label>
                    <input type="text" class="form-control" name="phone" >
                </div>

                <div class="form-group">
                    <label for="">Пароль</label>
                    <input type="text" class="form-control" name="phone"  value="123456">
                </div>

                <div class="form-group">
                    <label for="">id_1c</label>
                    <input type="text" class="form-control" name="id_1c" >
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="winning_access" value="1" id="winning_access">
                    <label class="form-check-label" for="winning_access">winning_access</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="payout_access" value="1" id="payout_access">
                    <label class="form-check-label" for="payout_access">payout_access</label>
                </div>
                <h6 class="mt-5">Роль</h6>
               <div>
                   @foreach($roles as $role)
                       <div class="form-check">
                           <input type="checkbox" class="form-check-input" name="roles[]" value="{{$role->id}}" id="role_{{$role->id}}">
                           <label class="form-check-label" for="role_{{$role->id}}">{{$role->description}}</label>
                       </div>
                   @endforeach
               </div>

            </div>
            <div class="col-md-3">
                <h6 class="">Торговые</h6>
                <div>
                    @foreach($salesreps as $salesrep)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input"   name="salesreps[]" value="{{$salesrep->id}}" id="salesrep_{{$salesrep->id}}">
                            <label class="form-check-label" for="salesrep_{{$salesrep->id}}">{{$salesrep->name}}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <h6 class="">Водитель</h6>
                <div>
                    @foreach($drivers as $driver)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input"   name="drivers[]" value="{{$driver->id}}" id="driver_{{$driver->id}}">
                            <label class="form-check-label" for="driver_{{$driver->id}}">{{$driver->name}}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
