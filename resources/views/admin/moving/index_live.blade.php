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
                </tr>
                </thead>
                <tbody>
                @foreach($movings as $moving)
                    @if($moving->deleted_at)
                        <tr class="bg-red">
                    @elseif($moving->removed_at)
                        <tr class="bg-black">
                    @else
                        <tr>
                            @endif
                            <td>{{$moving->id}}
                            </td>
                            <td class="project-actions text-left">
                                <a class="btn btn-primary btn-sm" href="{{route('admin.moving.show',$moving->id)}}">
                                    <i class="fas fa-folder">
                                    </i>
                                </a>

                            </td>
                            <td>
                                @if($moving->store?->counteragent_id)
                                   {{$moving->store->counteragent->name}}
                                @endif
                            </td>
                            <td>
                                @if($moving->store?->counteragent_id)
                                    {{$moving->store?->counteragent?->bin}}
                                @endif
                            </td>
                            <td>
                                <a href="{{route('admin.store.show',$moving->store_id)}}">{{$moving->store?->name}}</a>
                            </td>
                            <td>{{$moving->status}}</td>
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
