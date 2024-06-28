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
            <table class="table table-hover text-nowrap table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th></th>
                    <th>ТТ</th>
                    <th>Статус</th>
                    <th>Продавец</th>
                    <th>Дата создание</th>
                </tr>
                </thead>
                <tbody>
                @foreach($inventories as $inventory)
                    <tr>
                        <td>{{$inventory->id}}
                        </td>
                        <td class="project-actions text-left">
                            <a class="btn btn-primary btn-sm" href="{{route('admin.inventory.show',$inventory->id)}}">
                                <i class="fas fa-folder">
                                </i>
                            </a>
                            @if(Auth::id() == 1)
                                <a class="btn btn-danger btn-sm" href="{{route('admin.inventory.delete',$inventory->id)}}">
                                    <i class="fas fa-trash">
                                    </i>
                                </a>
                                <a class="btn btn-warning btn-sm" href="{{route('admin.inventory.edit',$inventory->id)}}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </a>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('admin.store.show',$inventory->store_id)}}">
                                {{$inventory->store?->name}}
                            </a>
                        </td>
                        <td>
                            @if($inventory->status == 2)
                                <span class="badge badge-success">активен</span>
                            @else
                                <span class="badge badge-warning">сохранен</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{route('admin.user.show',$inventory->user_id)}}">
                                {{$inventory->user->name}}
                            </a>
                        </td>


                        <td>{{$inventory->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

    </div>
    {{$inventories->links()}}
</div>
