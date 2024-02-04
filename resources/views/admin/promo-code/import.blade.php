@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.promo-code.importStore')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger col-12">
                    <ul class="list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li >{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Файл</label>
                    <input type="file" class="form-control" name="file" required accept=".xlsx, .xls, .csv"/>
                </div>
            </div>

        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
