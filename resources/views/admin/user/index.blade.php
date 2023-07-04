@extends('admin.layouts.index')

@section('content-header-title','Пользователи')
@section('content-header-right')
    <a href="{{route('admin.user.create')}}" class="btn btn-warning btn-sm">создать</a>

@endsection
@section('content')

    @livewire('admin.user-index')
@endsection
