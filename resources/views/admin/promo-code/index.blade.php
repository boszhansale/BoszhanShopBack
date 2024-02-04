@extends('admin.layouts.index')

@section('content-header-title','Заявки')
@section('content-header-right')
    @if(Auth::id() == 1)
        <a href="{{route('admin.promo-code.create')}}" class="btn btn-info btn-sm">создать</a>
    @endif
    <a href="{{route('admin.promo-code.import')}}" class="btn btn-info btn-sm">импорт Excel</a>
@endsection
@section('content')
    @if(Session::has('info_messages'))
        <div class="alert alert-info">
            <ul>
                @foreach(Session::get('info_messages') as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @livewire('admin.promo-code-index')
@endsection
