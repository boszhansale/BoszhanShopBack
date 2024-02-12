@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.promo-code.store')}}" method="post" enctype="multipart/form-data">
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
                    <label for="">ФИО</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="form-group">
                    <label for="">Телефон номер(без +7 и 8)</label>
                    <input type="text" class="form-control" name="phone" required placeholder="7001112233">
                </div>

                <div class="form-group">
                    <label for="">скидка %</label>
                    <input type="number" class="form-control" name="discount" required value="10">
                </div>
                <div class="form-group">
                    <label for="">старт</label>
                    <input type="datetime-local" class="form-control" name="start" required value="{{now()}}">
                </div>
                <div class="form-group">
                    <label for="">конец</label>
                    <input type="datetime-local" class="form-control" name="end" required value="{{now()->addHours(48)}}">
                </div>


            </div>

        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
