@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.inventory.update',$inventory->id)}}" method="post"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div>{{$error}}</div>
                @endforeach
            @endif
            @livewire('admin.inventory-edit',['inventoryId'=>$inventory->id])
        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить и перерасчет</button>
    </form>
@endsection
