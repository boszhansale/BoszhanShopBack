@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.brand.update',$brand->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Бренд</label>
                    <input type="text" class="form-control" name="name" required value="{{$brand->name}}">
                </div>
            </div>
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
