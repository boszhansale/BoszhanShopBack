<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <small>поиск</small>
                    <input wire:model="search" type="search" name="search" placeholder="поиск" class="form-control">
                </div>

                <div class="col-md-2">
                    <small>магазин</small>

                    <select wire:model="storeId" class="form-control">
                        <option value="">все</option>
                        @foreach($stores as $store)
                            <option value="{{$store->id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <small>даты создания заявки</small>
                    <input wire:model="start_created_at" type="date" required class="form-control">
                    <input wire:model="end_created_at" type="date" required class="form-control">
                </div>

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover text-nowrap">
                <thead>
                <tr>
                    <th>ID</th>
                    <th></th>
                    <th>ТТ</th>
                    <th>Операция</th>
                    <th>продажа</th>
                    <th>Продавец</th>
                    <th>сумма</th>
                    <th>Дата создание</th>
                </tr>
                </thead>
                <tbody>
                @foreach($refunds as $refund)
                    <tr>
                        <td>{{$refund->id}}
                        </td>
                        <td class="project-actions text-left">
                            <a class="btn btn-primary btn-sm" href="{{route('admin.refund.show',$refund->id)}}">
                                <i class="fas fa-folder">
                                </i>
                            </a>
{{--                            <a class="btn btn-danger btn-sm" href="{{route('admin.refund.delete',$refund->id)}}">--}}
{{--                                <i class="fas fa-trash">--}}
{{--                                </i>--}}
{{--                            </a>--}}

                        </td>
                        <td>
                            <a href="{{route('admin.store.show',$refund->store_id)}}">{{$refund->store?->name}}</a>
                        </td>
                        <td>{{$refund->typeInfo()}}</td>
                        <td>
                            <a target="_blank"
                               href="{{route('admin.order.show',$refund->order_id)}}">{{$refund->order_id}}</a>
                        </td>
                        <td>
                            <a href="{{route('admin.user.show',$refund->user_id)}}">{{$refund->user->name}}</a>
                        </td>

                        <td class="price">{{$refund->total_price}}</td>

                        <td>{{$refund->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

    </div>
    {{$refunds->links()}}
</div>
