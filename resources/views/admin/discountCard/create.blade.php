@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.discountCard.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="store_id" value="{{$storeId}}">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">номер телефона без 8,без +7 и без пробелов </label>
                    <input type="number" min="1000000000" max="9999999999" class="form-control" name="phone" required placeholder="7001112233">
                </div>

                <div class="form-group">
                    <label for="">Скидка %</label>
                    <input type="number" min="0" max="99" class="form-control" name="discount" value="0">
                </div>
                <div class="form-group">
                    <label for="">Кэшбэк %</label>
                    <input type="number" min="0" max="99" class="form-control" name="cashback" value="0">
                </div>
            </div>
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
