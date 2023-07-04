@extends('admin.layouts.index')

@section('content-header-title','Заявки')
@section('content-header-right')
@endsection
@section('content')
    @livewire('admin.refund-index',['userId' => $userId,'storeId' => $storeId,'counteragentId'=> $counteragentId])
@endsection
