<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
{{--                <div class="col-md-2">--}}
{{--                    <small>поиск</small>--}}
{{--                    <input wire:model="search" type="search" name="search" placeholder="поиск" class="form-control">--}}
{{--                </div>--}}

                <div class="col-md-2">
                    <small>продавец</small>

                    <select wire:model="userId" class="form-control">
                        <option value="">все</option>
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
                    <div class="col-md-2">
                        <small>торговая точка</small>
                        <select wire:model="storeId" class="form-control">
                            <option value="">все</option>
                            @foreach($stores as $store)
                                <option value="{{$store->id}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                    </div>

                <div class="col-md-2">
                    <small>даты создания заявки</small>
                    <input wire:model="start_created_at" type="date"  class="form-control">
                    <input wire:model="end_created_at" type="date"  class="form-control">
                </div>
                <div class="col-md-2">
                    <a href="{{route('admin.order.productExcel',['start_created_at' => $start_created_at,'end_created_at' => $end_created_at,'store_id' => $storeId,'search' => $search])}}" target="_blank" class="btn btn-primary">скачать Excel</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover text-nowrap table-responsive">
                <thead>
                <tr>
                    <th></th>
                    <th>ТТ</th>
                    <th>Продавец</th>
                    <th>Артикул</th>
                    <th>позиция</th>
                    <th>ед.</th>
                    <th>кол.</th>
                    <th>цена</th>
                    <th>сумма</th>
{{--                    <th>Дата</th>--}}
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                        <tr>
                            <td class="project-actions text-left">
{{--                                <a class="btn btn-primary btn-sm" href="{{route('admin.order.show',$order->id)}}">--}}
{{--                                    <i class="fas fa-folder">--}}
{{--                                    </i>--}}
{{--                                </a>--}}
                            </td>

                            <td>
                                <a href="{{route('admin.store.show',$order->store_id)}}">{{$order->store->name}}</a>
                            </td>
                            <td>{{$order->user->name}}</td>
                            <td>{{$order->article}}</td>
                            <td>{{$order->name}}</td>
                            <td>{{$order->measure == 1 ?'шт':'кг'}}</td>
{{--                            <td>--}}
{{--                                <a href="{{route('admin.user.show',$order->user_id)}}">{{$order->user->name}}</a>--}}
{{--                            </td>--}}
                            <td>{{$order->count}}</td>
                            <td>{{$order->price}}</td>
                            <td>{{$order->all_price}}</td>
{{--                            <td>{{$order->created_at}}</td>--}}
                        </tr>
                        @endforeach
                </tbody>
            </table>

        </div>

    </div>
</div>
