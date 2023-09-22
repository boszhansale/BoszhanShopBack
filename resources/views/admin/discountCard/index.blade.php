@extends('admin.layouts.index')

@section('content-header-title','Дисконт карты')
@section('content-header-right')
    <a href="{{route('admin.discountCard.create',$storeId)}}" class="btn btn-info btn-sm  ">создать</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    <table class="table  table-bordered">
                        <thead>
                        <tr>
                            <th>Телефон номер</th>
                            <th>скидка</th>
                            <th>кэшбэк</th>
                            <th>сумма кэшбэка</th>
                            <th>дата</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($discountCards as $discountCard)
                            <tr>
                                <td>{{$discountCard->phone}}</td>
                                <td>{{$discountCard->discount}}%</td>
                                <td>{{$discountCard->cashback}}%</td>
                                <td class="price">{{$discountCard->cashback_total_price}}</td>
                                <td >{{$discountCard->created_at}}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{route('admin.discountCard.edit',$discountCard->id)}}"><i class="fas fa-pencil-alt"></i></a>
                                    <a class="btn btn-danger btn-sm" href="{{route('admin.discountCard.delete',$discountCard->id)}}" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
