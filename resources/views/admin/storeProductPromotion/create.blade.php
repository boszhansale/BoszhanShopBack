@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.storeProductPromotion.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="store_id" value="{{$store->id}}">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">дата с</label>
                    <input type="date" class="form-control" name="date_from" required>
                </div>
                <div class="form-group">
                    <label for="">дата до</label>
                    <input type="date" class="form-control" name="date_to" required>
                </div>
                <hr>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="online_sale" value="1" id="online_sale" >
                    <label class="form-check-label" for="online_sale">для Онлайн продаж</label>
                </div>
                <br>
                <div class="form-group">
                    <label for="">сумма условия</label>
                    <input type="text" class="form-control" name="price_condition" value="5000" required>
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">количество</label>
                    <input type="number" class="form-control" name="count" value="1" required>
                </div>
                <div class="form-group">
                    <label for="">цена за единицу</label>
                    <input type="text" class="form-control" name="price" value="1" required>
                </div>


                <div class="form-group">
                    <label for="">Подарочный продукт</label>
                    <select name="product_id" class="form-control" required>
                        <option value="null"></option>

                        @foreach($products as $product)
                            <option  value="{{$product->id}}">{{$product->article}}  {{$product->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
