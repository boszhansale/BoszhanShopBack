@extends('admin.layouts.index')

@section('content-header-title','скидки на продукты')
@section('content-header-right')
    <a href="{{route('admin.storeProductDiscount.create',$store->id)}}" class="btn btn-info btn-sm  ">создать</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    <table class="table  table-bordered">
                        <thead>
                        <tr>
                            <th>дата с</th>
                            <th>дата до</th>
                            <th>продукт</th>
                            <th>артикул</th>
                            <th>скидка по процент</th>
                            <th>новая цена</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productDiscounts as $productDiscount)
                            <tr>
                                <td>{{$productDiscount->date_from}}</td>
                                <td>{{$productDiscount->date_to}}</td>
                                <td><a href="{{route('admin.product.edit',$productDiscount->product_id)}}">{{$productDiscount->name}} </a> </td>
                                <td>{{$productDiscount->article}}</td>
                                <td>{{$productDiscount->discount}}%</td>
                                <td class="price">{{$productDiscount->price}}</td>
                                <td>
{{--                                    <a class="btn btn-info btn-sm" href=""><i class="fas fa-pencil-alt"></i></a>--}}
                                    <a class="btn btn-danger btn-sm" href="{{route('admin.storeProductDiscount.delete',$productDiscount->id)}}" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
