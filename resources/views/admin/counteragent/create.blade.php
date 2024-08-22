@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.counteragent.store')}}" method="post"
          enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">название</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">группа</label>
                        <select name="group_id" class="form-control">
                            <option value="">Выберите группу</option>
                            @foreach(\App\Models\CounteragentGroup::all() as $group)
                                <option value="{{$group->id}}">{{$group->nmae}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">id_1c</label>
                        <input type="text" class="form-control" name="id_1c" required>
                    </div>
                    <div class="form-group">
                        <label for="">БИН</label>
                        <input type="text" class="form-control" name="bin" required>
                    </div>
                    <div class="form-group">
                        <label for="">ИИК</label>
                        <input type="text" class="form-control" name="iik">
                    </div>
                    <div class="form-group">
                        <label for="">БИК</label>
                        <input type="text" class="form-control" name="bik">
                    </div>

                    <div class="form-group">
                        <label for="">Скидка %</label>
                        <input type="number" class="form-control" name="discount" value="0">
                    </div>
                    <div class="form-group">
                        <label for="delivery_time">доставить до</label>
                        <input type="time" name="delivery_time" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Тип оплаты</label>
                        <select name="payment_type_id" required class="form-control">
                            @foreach($paymentTypes as $paymentType)
                                <option value="{{$paymentType->id}}">{{$paymentType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">цена</label>
                        <select name="price_type_id" required class="form-control">
                            @foreach($priceTypes as $priceType)
                                <option value="{{$priceType->id}}">{{$priceType->name}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="enabled" value="1" id="enabled" checked>
                        <label class="form-check-label" for="enabled">активный</label>
                    </div>


                </div>
                <div class="col-md-6">
                    <h6 class="">Доступ для ТП </h6>
                    <div>
                        @foreach($salesreps as $user)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="salesreps[]" value="{{$user->id}}"
                                       id="salesrep_{{$user->id}}">
                                <label class="form-check-label" for="salesrep_{{$user->id}}">{{$user->name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
