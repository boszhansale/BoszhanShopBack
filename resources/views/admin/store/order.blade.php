@extends('admin.layouts.index')

@section('content-header-title')
   Торговая точка: <a href="{{route('admin.store.show',$store->id)}}">{{$store->name}}</a>
@endsection

@section('content')
    @livewire('store-order-index', ['store_id' => $store->id])
@endsection

