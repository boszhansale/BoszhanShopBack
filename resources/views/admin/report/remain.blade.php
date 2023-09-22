@extends('admin.layouts.index')

@section('content-header-title',$store->name)

@section('content')
    @livewire('admin.report-remain',['storeId' => $store->id])
@endsection



