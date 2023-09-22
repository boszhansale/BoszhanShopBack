@extends('admin.layouts.index')

@section('content-header-title',$store->name)

@section('content')
    @livewire('admin.store-z-report',['storeId' => $store->id])
@endsection



