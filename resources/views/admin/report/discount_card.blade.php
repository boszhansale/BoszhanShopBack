@extends('admin.layouts.index')

@section('content-header-title',$store->name)

@section('content')
    @livewire('admin.report-discount-card',['storeId' => $store->id])
@endsection



