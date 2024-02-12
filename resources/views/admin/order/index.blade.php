@extends('admin.layouts.index')

@section('content-header-title','Заявки')
@section('content-header-right')
@endsection
@section('content')
    @livewire('admin.order-index',['userId' => $userId,'storeId' => $storeId,'counteragentId'=> $counteragentId,'discountPhone' => $discountPhone,'start_created_at' => $startCreatedAt,'end_created_at' => $endCreatedAt])
@endsection
