@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.store.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">название</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="form-group">
                    <label for="">Телефон номер</label>
                    <input type="text" class="form-control" name="phone">
                </div>

                <div class="form-group">
                    <label for="">Адрес</label>
                    <input type="text" class="form-control" name="address">
                </div>
                <div class="row">
                    <div class="col form-group">
                        <label for="">lat</label>
                        <input type="text" class="form-control" name="lat">
                    </div>
                    <div class="col form-group">
                        <label for="">lng</label>
                        <input type="text" class="form-control" name="lng">
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Скидка %</label>
                    <input type="text" class="form-control" name="discount" value="0">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="discount_position" value="1" id="discount_position" >
                    <label class="form-check-label" for="discount_position">скидка на позицию</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="enabled" value="1" id="enabled" checked>
                    <label class="form-check-label" for="enabled">активный</label>
                </div>
                <br>
                <div class="form-group">
                    <label for="">Контрагент</label>
                    <select name="counteragent_id" class="form-control">
                        @foreach($counteragents as $counteragent)
                            <option {{$counteragent->id == 3481 ? 'selected':''}} value="{{$counteragent->id}}">{{$counteragent->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
