@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.product.update',$product->id)}}" method="post"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="">Название</label>
                    <input type="text" class="form-control" name="name" value="{{$product->name}}">
                </div>
                <div class="form-group">
                    <label for="">ID 1C</label>
                    <input type="text" class="form-control" name="id_1c" value="{{$product->id_1c}}">
                </div>
                <div class="form-group">
                    <label for="">Артикул</label>
                    <input type="text" class="form-control" name="article" value="{{$product->article}}">
                </div>
                <div class="form-group ">
                    <label for="">Мерка</label>
                    <select name="measure" class="form-control">
                        <option {{ $product->measure == 1 ? 'selected':'' }} value="1">штука(1)</option>
                        <option {{ $product->measure == 2 ? 'selected':'' }} value="2">кг(2)</option>
                    </select>
                </div>

                <div class="form-group ">
                    <label for="">Категория</label>
                    <select name="category_id" class="form-control">
                        @foreach($categories as $category)
                            <option
                                {{$product->category_id == $category->id ? 'selected':''}} value="{{$category->id}}">{{$category->brand?->name}}
                                - {{$category->name}}</option>
                        @endforeach
                    </select>
                </div>


                <div class="form-group">
                    <label for="">barcode 1</label>
                    <input type="text" class="form-control" name="barcode_1" value="{{$product->barcode_1}}">
                </div>
                <div class="form-group">
                    <label for="">barcode 2</label>
                    <input type="text" class="form-control" name="barcode_2" value="{{$product->barcode_2}}">
                </div>
                <div class="form-group">
                    <label for="">barcode 3</label>
                    <input type="text" class="form-control" name="barcode_3" value="{{$product->barcode_3}}">
                </div>
                <div class="form-group">
                    <label for="">barcode 4</label>
                    <input type="text" class="form-control" name="barcode_4" value="{{$product->barcode_4}}">
                </div>
                <div class="form-group">
                    <label for="">остаток</label>
                    <input type="number" class="form-control" name="remainder" value="{{$product->remainder}}">
                </div>


                <div class="form-group">
                    <label for="">Скидка %</label>
                    <input type="number" class="form-control" name="discount" value="{{$product->discount ?? 0}}">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="purchase"
                           {{$product->purchase ? "checked":''}}   id="purchase">
                    <label class="form-check-label" for="purchase">Доступ к продажу</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="return"
                           {{$product->return ? "checked":''}}   id="return">
                    <label class="form-check-label" for="return">Доступ к возврату</label>
                </div>

                <br>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="hit"
                           {{$product->hit ? "checked":''}}   id="product_hit">
                    <label class="form-check-label" for="product_hit">ярлык Хит</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="new"
                           {{$product->new ? "checked":''}}  id="product_new">
                    <label class="form-check-label" for="product_new">ярлык Новый</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="action"
                           {{$product->action ? "checked":''}}   value="1" id="product_action">
                    <label class="form-check-label" for="product_action">ярлык Акция</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="discount_5"
                           {{$product->discount_5 ? "checked":''}} value="1" id="product_discount_5">
                    <label class="form-check-label" for="product_discount_5"> ярлык Скидка 5%</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="discount_10"
                           {{$product->discount_10 ? "checked":''}} value="1" id="product_discount_10">
                    <label class="form-check-label" for="product_discount_10"> ярлык Скидка 10%</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="discount_15"
                           {{$product->discount_15 ? "checked":''}} value="1" id="product_discount_15">
                    <label class="form-check-label" for="product_discount_15"> ярлык Скидка 15%</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="discount_20"
                           {{$product->discount_20 ? "checked":''}} value="1" id="product_discount_20">
                    <label class="form-check-label" for="product_discount_20"> ярлык Скидка 20%</label>
                </div>
                <div class="form-group">
                    <label for="">фото</label>
                    <input type="file" multiple name="images[]" class="form-control" accept="image/*">
                </div>


            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">barcode</label>
                    <input type="text" class="form-control" name="barcode" value="{{$product->barcode}}">
                </div>

                @foreach($product->barcodes as $barcode)
                    <div class="form-group">
                        <label for="">barcode {{$loop->iteration}}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="barcodes[{{$loop->index}}]"value="{{$barcode->barcode}}">
                            <div class="input-group-append">
                                <a href="{{route('admin.product.barcode.delete',$barcode->id)}}" class="input-group-text btn-danger" >
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>

                        </div>

                    </div>

                @endforeach
                <button type="button" class="btn btn-default mt-3" data-toggle="modal" data-target="#modal-barcode">
                    добавить поле
                </button>
            </div>
            <div class="col-md-5">
                @foreach($priceTypes as $k => $priceType)
                    <div>
                        <label for="">
                            {{$priceType->name}}
                            <small>{{$priceType->description}}</small>
                        </label>
                        <input type="hidden" name="price_types[{{$k}}][price_type_id]" value="{{$priceType->id}}">
                        @if($product->prices()->where('price_type_id',$priceType->id)->exists())
                            <input class="form-control" type="number" name="price_types[{{$k}}][price]"
                                   value="{{$product->prices()->where('price_type_id',$priceType->id)->first()->price}}">
                        @else
                            <input class="form-control" type="number" name="price_types[{{$k}}][price]" value="0">
                        @endif
                    </div>
                @endforeach
                <br>
{{--                <div class="row">--}}
{{--                    <h5>Цены для Контрагентов</h5>--}}

{{--                    <div class="col-md-12">--}}
{{--                        @foreach($product->counteragentPrices as $k => $counteragentPrice)--}}
{{--                            <div>--}}
{{--                                <label for="">--}}
{{--                                    {{$counteragentPrice->counteragent->name}}--}}

{{--                                </label>--}}
{{--                                <input type="hidden" name="counteragent_prices[{{$k}}][counteragent_id]"--}}
{{--                                       value="{{$counteragentPrice->counteragent_id}}">--}}
{{--                                <div class="row">--}}
{{--                                    <input class="form-control col-6" type="number"--}}
{{--                                           name="counteragent_prices[{{$k}}][price]"--}}
{{--                                           value="{{$counteragentPrice->price}}">--}}
{{--                                    <a href="{{route('admin.product.counteragentPriceDelete',$counteragentPrice->id)}}"--}}
{{--                                       class="btn btn-danger">удалить</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}

{{--                    <button type="button" class="btn btn-default mt-3" data-toggle="modal" data-target="#modal-default">--}}
{{--                        добавить цену--}}
{{--                    </button>--}}
{{--                </div>--}}
            </div>
        </div>

        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>

    <div class="modal fade" id="modal-default" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <form action="{{route('admin.product.counteragentPriceStore',$product->id)}}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Контрагент</label>
                            <select name="counteragent_id" class="form-control">
                                @foreach($counteragents as $c)
                                    <option value="{{$c->id}}">{{$c->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Цена</label>
                            <input type="number" class="form-control" name="price">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">отмена</button>
                        <button type="submit" class="btn btn-primary">сохранить</button>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-barcode" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <form action="{{route('admin.product.barcode.store',$product->id)}}" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="">Barcode</label>
                                <input type="text" class="form-control" name="barcode">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">отмена</button>
                            <button type="submit" class="btn btn-primary">сохранить</button>
                        </div>
                    </div>
            </form>

        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection
