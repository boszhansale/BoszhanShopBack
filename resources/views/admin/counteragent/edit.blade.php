@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.counteragent.update',$counteragent->id)}}" method="post"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">название</label>
                    <input type="text" class="form-control" name="name" required value="{{$counteragent->name}}">
                </div>
                <div class="form-group">
                    <label for="">группа</label>
                    <select name="group_id" class="form-control">
                        <option value="">выберите</option>
                        @foreach(\App\Models\CounteragentGroup::all() as $group)
                            <option value="{{$group->id}}" {{$group->id == $counteragent->group_id ? 'selected' : ''}}>{{$group->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">id_1c</label>
                    <input type="text" class="form-control" name="id_1c" required value="{{$counteragent->id_1c}}">
                </div>
                <div class="form-group">
                    <label for="">БИН</label>
                    <input type="text" class="form-control" name="bin" required value="{{$counteragent->bin}}">
                </div>
                <div class="form-group">
                    <label for="">ИИК</label>
                    <input type="text" class="form-control" name="iik" value="{{$counteragent->iik}}">
                </div>
                <div class="form-group">
                    <label for="">БИК</label>
                    <input type="text" class="form-control" name="bik" value="{{$counteragent->bik}}">
                </div>
                <div class="form-group">
                    <label for="">Скидка %</label>
                    <input type="number" class="form-control" name="discount" value="{{$counteragent->discount}}">
                </div>
                <div class="form-group">
                    <label for="delivery_time">доставить до</label>
                    <input type="time" name="delivery_time" class="form-control"
                           value="{{$counteragent->delivery_time}}">
                </div>
                <div class="form-group">
                    <label for="">Тип оплаты</label>
                    <select name="payment_type_id" required class="form-control">
                        @foreach($paymentTypes as $paymentType)
                            <option
                                {{$counteragent->payment_type_id == $paymentType->id ? 'selected':''}} value="{{$paymentType->id}}">{{$paymentType->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">цена</label>
                    <select name="price_type_id" required class="form-control">
                        @foreach($priceTypes as $priceType)
                            <option
                                {{$counteragent->price_type_id == $priceType->id ? 'selected':''}} value="{{$priceType->id}}">{{$priceType->name}}</option>
                        @endforeach
                    </select>
                </div>


                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="enabled" value="1"
                           id="enabled" {{$counteragent->enabled ? 'checked':''}} >
                    <label class="form-check-label" for="enabled">активный</label>
                </div>


            </div>
            <div class="col-md-6">
                <h6 class="">Доступ для ТП </h6>
                <div>
                    @foreach($salesreps as $user)
                        <div class="form-check">
                            <input type="checkbox"
                                   {{$user->counteragents()->where('counteragents.id',$counteragent->id)->exists() ? "checked" : ''}}  class="form-check-input"
                                   name="salesreps[]" value="{{$user->id}}" id="salesrep_{{$user->id}}">
                            <label class="form-check-label" for="salesrep_{{$user->id}}">{{$user->name}}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
