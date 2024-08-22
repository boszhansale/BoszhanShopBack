@extends('admin.layouts.index')

@section('content-header-title')
   Контрагент: <a href="{{route('admin.counteragent.show',$counteragent->id)}}">{{$counteragent->name}}</a>
@endsection

@section('content')
    @livewire('counteragent-order-index', ['counteragent_id' => $counteragent->id])
@endsection

