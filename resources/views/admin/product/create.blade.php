@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.product.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Название</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="form-group">
                    <label for="">ID 1C</label>
                    <input type="text" class="form-control" name="id_1c">
                </div>
                <div class="form-group">
                    <label for="">Артикул</label>
                    <input type="text" class="form-control" name="article" required>
                </div>
                <div class="form-group ">
                    <label for="">Мерка</label>
                    <select name="measure"  class="form-control">
                        <option  value="1">штука(1)</option>
                        <option  value="2">кг(2)</option>
                    </select>
                </div>
                <div class="form-group ">
                    <label for="">Категория</label>
                    <select name="category_id"  class="form-control">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->brand->name}} - {{$category->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">barcode</label>
                    <input type="text" class="form-control" name="barcode" >
                </div>





                <div class="form-group">
                    <label for="">barcode 1</label>
                    <input type="text" class="form-control" name="barcode_1" >
                </div>
                <div class="form-group">
                    <label for="">barcode 2</label>
                    <input type="text" class="form-control" name="barcode_2" >
                </div>
                <div class="form-group">
                    <label for="">barcode 3</label>
                    <input type="text" class="form-control" name="barcode_3" >
                </div>
                <div class="form-group">
                    <label for="">barcode 4</label>
                    <input type="text" class="form-control" name="barcode_4" >
                </div>
                <div class="form-group">
                    <label for="">остаток</label>
                    <input type="number" class="form-control" name="remainder" >
                </div>
                <div class="form-group">
                    <label for="">Скидка %</label>
                    <input type="number" class="form-control" name="discount" value="0">
                </div>
                <div class="form-check">
                    <input type="checkbox" checked class="form-check-input" name="purchase" value="1" id="purchase">
                    <label class="form-check-label" for="purchase">Доступ к продажу</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" checked class="form-check-input" name="return" value="1" id="return">
                    <label class="form-check-label" for="return">Доступ к возврату</label>
                </div>
                <br>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="hit" value="1" id="product_hit">
                    <label class="form-check-label" for="product_hit">ярлык Хит</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="new" value="1" id="product_new">
                    <label class="form-check-label" for="product_new">ярлык Новый</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="action" value="1" id="product_action">
                    <label class="form-check-label" for="product_action">ярлык Акция</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="discount_5" value="1" id="product_discount_5">
                    <label class="form-check-label" for="product_discount_5"> ярлык Скидка 5%</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="discount_10" value="1" id="product_discount_10">
                    <label class="form-check-label" for="product_discount_10"> ярлык Скидка 10%</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="discount_15" value="1" id="product_discount_15">
                    <label class="form-check-label" for="product_discount_15"> ярлык Скидка 15%</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="discount_20" value="1" id="product_discount_20">
                    <label class="form-check-label" for="product_discount_20"> ярлык Скидка 20%</label>
                </div>
                <div class="form-group">
                    <label for="">фото</label>
                    <input type="file" multiple name="images[]" class="form-control" accept="image/*">
                </div>

            </div>
            <div class="col-md-4">
                @foreach($priceTypes as $k => $priceType)
                    <div>
                        <label for="">
                            {{$priceType->name}}
                            <small>{{$priceType->description}}</small>
                        </label>
                        <input type="hidden" name="price_types[{{$k}}][price_type_id]" value="{{$priceType->id}}">
                        <input class="form-control" type="number" name="price_types[{{$k}}][price]" value="0">
                    </div>
                @endforeach
            </div>
{{--            <div class="col-md-4">--}}
{{--                @foreach($counteragents as $k => $counteragent)--}}
{{--                    <div>--}}
{{--                        <label for="">--}}
{{--                            {{$counteragent->name}}--}}
{{--                        </label>--}}
{{--                        <input type="hidden" name="counteragent_prices[{{$k}}][counteragent_id]" value="{{$counteragent->id}}">--}}
{{--                        <input class="form-control" type="number" name="counteragent_prices[{{$k}}][price]" value="0">--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
