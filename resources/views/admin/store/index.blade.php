@extends('admin.layouts.index')

@section('content-header-title','ТТ')
@section('content-header-right')
    <a href="{{route('admin.store.create')}}" class="btn btn-info btn-sm  ">создать</a>
@endsection
@section('content')
    @livewire('admin.store-index',['userId' => $userId,'counteragentId' => $counteragentId])
@endsection
