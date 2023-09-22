@extends('admin.layouts.index')

@section('content-header-title',$brand->name)
@section('content-header-right')
    <a href="{{route('admin.category.create',$brand->id)}}" class="btn btn-info btn-sm">создать</a>
@endsection
@section('content')

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>категория</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <th>{{$category->id}}</th>
                <th>
                    {{$category->name}}
                </th>

                <td class="project-actions text-right">
                    <a class="btn btn-info btn-sm" href="{{route('admin.category.edit',$category->id)}}">
                        <i class="fas fa-pencil-alt">
                        </i>
                        изменить
                    </a>
                    <a  class="btn btn-danger btn-sm" href="{{route('admin.category.delete',$category->id)}}" onclick="return confirm('Удалить?')">
                        <i class="fas fa-trash"></i>
                        удалить
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
