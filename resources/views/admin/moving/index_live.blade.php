<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <small>поиск</small>
                    <input wire:model="search" type="search" name="search" placeholder="поиск" class="form-control">
                </div>
                <div class="col-md-2">
                    <small>операция</small>

                    <select wire:model="operation" class="form-control">
                        <option value="">все</option>
                        <option value="1">с склада</option>
                        <option value="2">на склад</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <small>торговый точка</small>

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
                    <th></th>
                    <th>ТТ</th>
                    <th>Продавец</th>
                    <th>сумма</th>
                    <th>Дата создание</th>
                </tr>
                </thead>
                <tbody>
                @foreach($movings as $moving)
                        <tr>
                            <td>{{$moving->id}}
                            </td>
                            <td class="project-actions text-left">
                                <a class="btn btn-primary btn-sm" href="{{route('admin.moving.show',$moving->id)}}">
                                    <i class="fas fa-folder">
                                    </i>
                                </a>
                                <a class="btn btn-warning btn-sm" href="{{route('admin.moving.edit',$moving->id)}}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </a>
                                <a class="btn btn-danger btn-sm" href="{{route('admin.moving.delete',$moving->id)}}">
                                    <i class="fas fa-trash">
                                    </i>
                                </a>

                            </td>
                            <td>
                                {{$moving->operation == 1 ? 'с склада':'на склад'}}
                            </td>
                            <td>
                                <a href="{{route('admin.store.show',$moving->store_id)}}">{{$moving->store?->name}}</a>
                            </td>
                            <td>
                                <a href="{{route('admin.user.show',$moving->user_id)}}">{{$moving->user->name}}</a>
                            </td>

                            <td class="price">{{$moving->total_price}}</td>

                            <td>{{$moving->created_at}}</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>

        </div>

    </div>
    {{$movings->links()}}
</div>
