@extends('admin.layouts.index')
@section('content')
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        {{$product->name}}
                    </div>
                    <div class="card-header">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{$product->id}}</td>
                            </tr>
                            <tr>
                                <th>id_1c</th>
                                <td>{{$product->id_1c}}</td>
                            </tr>
                            <tr>
                                <th>артикул</th>
                                <td>{{$product->article}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection
