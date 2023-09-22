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
                @foreach($receipts as $receipt)
                    @if($receipt->deleted_at)
                        <tr class="bg-red">
                    @elseif($receipt->removed_at)
                        <tr class="bg-black">
                    @else
                        <tr>
                            @endif
                            <td>{{$receipt->id}}
                            </td>
                            <td class="project-actions text-left">
                                <a class="btn btn-primary btn-sm" href="{{route('admin.receipt.show',$receipt->id)}}">
                                    <i class="fas fa-folder">
                                    </i>
                                </a>


                            </td>
                            <td>
                                @if($receipt->store?->counteragent_id)
                                   {{$receipt->store->counteragent->name}}
                                @endif
                            </td>
                            <td>
                                @if($receipt->store?->counteragent_id)
                                    {{$receipt->store?->counteragent?->bin}}
                                @endif
                            </td>
                            <td>
                                <a href="{{route('admin.store.show',$receipt->store_id)}}">{{$receipt->store?->name}}</a>
                            </td>
                            <td>{{$receipt->status}}</td>
                            <td>
                                <a href="{{route('admin.user.show',$receipt->user_id)}}">{{$receipt->user->name}}</a>
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
