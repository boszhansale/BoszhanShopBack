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
                    <th>Продавец</th>
                    <th>Инвентаризация</th>
                    <th>сумма</th>
                    <th>Дата создание</th>
                </tr>
                </thead>
                <tbody>
                @foreach($receipts as $receipt)
                        <tr>
                            <td>{{$receipt->id}}
                            </td>
                            <td class="project-actions text-left">
                                <a class="btn btn-primary btn-sm" href="{{route('admin.receipt.show',$receipt->id)}}">
                                    <i class="fas fa-folder">
                                    </i>
                                </a>
                                <a class="btn btn-warning btn-sm" href="{{route('admin.receipt.edit',$receipt->id)}}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </a>
                                <a class="btn btn-danger btn-sm" href="{{route('admin.receipt.delete',$receipt->id)}}">
                                    <i class="fas fa-trash">
                                    </i>
                                </a>


                            </td>
                            <td>
                                <a href="{{route('admin.store.show',$receipt->store_id)}}">{{$receipt->store?->name}}</a>
                            </td>
                            <td>
                                <a href="{{route('admin.user.show',$receipt->user_id)}}">{{$receipt->user->name}}</a>
                            </td>
                            <td>
                                @if($receipt->inventory_id)
                                    <a href="{{route('admin.inventory.show',$receipt->inventory_id)}}">{{$receipt->description}}</a>
                                @endif
                            </td>


                            <td class="price">{{$receipt->total_price}}</td>

                            <td>{{$receipt->created_at}}</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>

        </div>

    </div>
    {{$receipts->links()}}
</div>
