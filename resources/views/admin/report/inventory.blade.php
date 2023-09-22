@extends('admin.layouts.index')

@section('content-header-title','Продажа')
@section('content-header-right')
@endsection
@section('content')
    @livewire('admin.inventory-index',['storeId' => $store->id])
@endsection
