<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <small>поиск</small>
                    <input wire:model="search" type="search" name="search" placeholder="поиск" class="form-control">
                </div>

                <div class="col-md-2">
                    <small>продавец</small>

                    <select wire:model="salesrepId" class="form-control">
                        <option value="">все</option>
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
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
                    <th>Контрагент</th>
                    <th>Контрагент(BIN)</th>
                    <th>ТТ</th>
                    <th>Статус</th>
                    <th>Продавец</th>
                    <th>сумма</th>
                    <th>Дата создание</th>
                    <th>тип оплаты</th>
                </tr>
                </thead>
                <tbody>
                @foreach($inventories as $inventory)
                    @if($inventory->deleted_at)
                        <tr class="bg-red">
                    @elseif($inventory->removed_at)
                        <tr class="bg-black">
                    @else
                        <tr>
                            @endif
                            <td>{{$inventory->id}}
                            </td>
                            <td class="project-actions text-left">
                                <a class="btn btn-primary btn-sm" href="{{route('admin.inventory.show',$inventory->id)}}">
                                    <i class="fas fa-folder">
                                    </i>
                                </a>
                                {{--                                    <a class="btn btn-info btn-sm" href="{{route('admin.inventory.edit',$inventory->id)}}">--}}
                                {{--                                        <i class="fas fa-pencil-alt">--}}
                                {{--                                        </i>--}}
                                {{--                                    </a>--}}

                            </td>
                            <td>
                                @if($inventory->store?->counteragent_id)
                                    <a href="{{route('admin.counteragent.show',$inventory->store->counteragent_id)}}">{{$inventory->store->counteragent->name}}</a>
                                @endif
                            </td>
                            <td>
                                @if($inventory->store?->counteragent_id)
                                    {{$inventory->store?->counteragent?->bin}}
                                @endif
                            </td>
                            <td>
                                <a href="{{route('admin.store.show',$inventory->store_id)}}">{{$inventory->store?->name}}</a>
                            </td>
                            <td>{{$inventory->status}}</td>
                            <td>
                                <a href="{{route('admin.user.show',$inventory->user_id)}}">{{$inventory->user->name}}</a>
                            </td>

                            <td class="price">{{$inventory->total_price}}</td>

                            <td>{{$inventory->created_at}}</td>
                            <td>{{$inventory->payment_type}}</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>

        </div>

    </div>
    {{$inventories->links()}}
</div>
