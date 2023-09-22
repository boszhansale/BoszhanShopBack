@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.category.update',$category->id)}}" method="post"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">категория</label>
                    <input type="text" class="form-control" name="name" required value="{{$category->name}}">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="enabled" id="enabled"
                           value="1" {{$category->enabled ? 'checked':''}}>
                    <label for="enabled">enabled</label>
                </div>
            </div>
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
