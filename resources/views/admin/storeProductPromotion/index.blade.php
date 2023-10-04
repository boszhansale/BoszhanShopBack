@extends('admin.layouts.index')

@section('content-header-title','скидки на продукты')
@section('content-header-right')
    <a href="{{route('admin.storeProductPromotion.create',$store->id)}}" class="btn btn-info btn-sm  ">создать</a>
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
                            <th>кол.</th>
                            <th>цена за ед.</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($productPromotions as $productPromotion)
                            <tr>
                                <td>{{$productPromotion->date_from}}</td>
                                <td>{{$productPromotion->date_to}}</td>
                                <td><a href="{{route('admin.product.edit',$productPromotion->product_id)}}">{{$productPromotion->name}} </a> </td>
                                <td>{{$productPromotion->article}}</td>
                                <td>{{$productPromotion->count}}</td>
                                <td class="price">{{$productPromotion->price}}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{route('admin.storeProductPromotion.edit',$productPromotion->id)}}"><i class="fas fa-pencil-alt"></i></a>
                                    <a class="btn btn-danger btn-sm" href="{{route('admin.storeProductPromotion.delete',$productPromotion->id)}}" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
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
