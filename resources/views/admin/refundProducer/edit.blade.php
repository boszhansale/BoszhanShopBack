@extends('admin.layouts.index')
@section('content')
    <form class="product-edit" action="{{route('admin.order.update',$refundProducer->id)}}" method="post"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div>{{$error}}</div>
                @endforeach
            @endif
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Торговый точка</label>
                    <select name="store_id" class="form-control">
                        @foreach($refundProducer->salesrep->stores()->orderBy('name')->get() as $store)
                            <option
                                {{$store->id == $refundProducer->store_id ? 'selected':''}} value="{{$store->id}}">{{$store->name}}
                                -> {{$store->address}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Торговый представитель</label>
                    <select name="salesrep_id" class="form-control" required>
                        @foreach($salesreps as $salesrep)
                            <option
                                {{$refundProducer->salesrep_id == $salesrep->id ? 'selected':''}} value="{{$salesrep->id}}">{{$salesrep->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Водитель</label>
                    <select name="driver_id" class="form-control" required>
                        @foreach($drivers as $driver)
                            <option
                                {{$refundProducer->driver_id == $driver->id ? 'selected':''}}  value="{{$driver->id}}">{{$driver->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Статус</label>
                    <select name="status_id" class="form-control" required>
                        @foreach($statuses as $status)
                            <option
                                {{$refundProducer->status_id == $status->id ? 'selected':''}}  value="{{$status->id}}">{{$status->description}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Тип оплаты</label>
                    <select name="payment_type_id" class="form-control" required>
                        @foreach($paymentTypes as $paymentType)
                            <option
                                {{$refundProducer->payment_type_id == $paymentType->id ? 'selected':''}}  value="{{$paymentType->id}}">{{$paymentType->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Статус оплаты</label>
                    <select name="payment_status_id" class="form-control" required>
                        @foreach($paymentStatuses as $paymentStatus)
                            <option
                                {{$refundProducer->payment_status_id == $paymentStatus->id ? 'selected':''}}  value="{{$paymentStatus->id}}">{{$paymentStatus->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="">Дата доставки</label>
                    <input type="date" name="delivery_date" class="form-control" required
                           value="{{\Carbon\Carbon::parse($refundProducer->delivery_date)->format('Y-m-d')}}">
                </div>
                <div class="form-group">
                    <label for="">Дата доставлено</label>
                    <input type="datetime-local" name="delivered_date" class="form-control" required
                           value="{{\Carbon\Carbon::parse($refundProducer->delivered_date)->format('Y-m-d H:i')}}">
                </div>
                <div class="form-group">
                    <label for="">сумма</label>
                    <input type="text" name="purchase_price" class="form-control" required
                           value="{{$refundProducer->purchase_price}}">
                </div>
                <div class="form-group">
                    <label for="">сумма возврата</label>
                    <input type="text" name="return_price" class="form-control" required
                           value="{{$refundProducer->return_price}}">
                </div>


            </div>

        </div>
        <button type="submit" class="mt-5 mb-10 btn btn-primary col-3 ">Сохранить</button>
    </form>
@endsection
