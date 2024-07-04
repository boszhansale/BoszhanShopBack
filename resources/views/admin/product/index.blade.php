@extends('admin.layouts.index')

@section('content-header-title','Продукты')
@section('content-header-right')
    <a href="{{route('admin.product.create')}}" class="btn btn-info btn-sm  ">создать</a>
    <a href="{{route('admin.product.priceParse')}}" class="btn btn-info btn-sm ">импорт цены</a>
@endsection
@section('content')
    <livewire:admin.product-index />
@endsection
