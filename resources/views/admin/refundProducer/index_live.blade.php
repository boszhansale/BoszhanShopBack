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
                @foreach($refundProducers as $refundProducer)
                    @if($refundProducer->deleted_at)
                        <tr class="bg-red">
                    @elseif($refundProducer->removed_at)
                        <tr class="bg-black">
                    @else
                        <tr>
                            @endif
                            <td>{{$refundProducer->id}}
                            </td>
                            <td class="project-actions text-left">
                                <a class="btn btn-primary btn-sm" href="{{route('admin.refundProducer.show',$refundProducer->id)}}">
                                    <i class="fas fa-folder">
                                    </i>
                                </a>
                                {{--                                    <a class="btn btn-info btn-sm" href="{{route('admin.order.edit',$refundProducer->id)}}">--}}
                                {{--                                        <i class="fas fa-pencil-alt">--}}
                                {{--                                        </i>--}}
                                {{--                                    </a>--}}

                            </td>
                            <td>
                                @if($refundProducer->store?->counteragent_id)
                                    <a href="{{route('admin.counteragent.show',$refundProducer->store->counteragent_id)}}">{{$refundProducer->store->counteragent->name}}</a>
                                @endif
                            </td>
                            <td>
                                @if($refundProducer->store?->counteragent_id)
                                    {{$refundProducer->store?->counteragent?->bin}}
                                @endif
                            </td>
                            <td>
                                <a href="{{route('admin.store.show',$refundProducer->store_id)}}">{{$refundProducer->store?->name}}</a>
                            </td>
                            <td>{{$refundProducer->status}}</td>
                            <td>
                                <a href="{{route('admin.user.show',$refundProducer->user_id)}}">{{$refundProducer->user->name}}</a>
                            </td>

                            <td class="price">{{$refundProducer->total_price}}</td>

                            <td>{{$refundProducer->created_at}}</td>
                            <td>{{$refundProducer->payment_type}}</td>
                        </tr>
                        @endforeach
                </tbody>
            </table>

        </div>

    </div>
    {{$refundProducers->links()}}
</div>
