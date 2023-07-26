@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.product.priceParse')}}" method="post"
          enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for=""></label>
                    <select name="price_type_id" class="form-control">
                        @foreach($priceTypes as $priceType)
                            <option value="{{$priceType->id}}">{{$priceType->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Excel файл</label>
                    <input type="file" multiple name="price" class="form-control"
                           accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                </div>

            </div>
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">парсинг</button>
    </form>
@endsection
