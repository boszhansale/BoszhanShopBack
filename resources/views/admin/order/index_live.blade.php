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

                    <select wire:model="userId" class="form-control">
                        <option value="">все</option>
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <small>даты создания заявки</small>
                    <input wire:model="start_created_at" type="date"  class="form-control">
                    <input wire:model="end_created_at" type="date"  class="form-control">
                </div>
                <div class="col-md-2">
                    <small>Тип оплаты</small>

                    <select wire:model="paymentType" class="form-control">
                        <option value="null">все</option>
                        <option value="0">нал</option>
                        <option value="1">безнал</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input wire:model="discountBool" type="checkbox" id="discount_bool"  class="">
                    <label for="discount_bool">Скидки</label>
                    <br>
                    <input wire:model="discountPhoneBool" type="checkbox" id="discount_phone_bool"  class="">
                    <label for="discount_phone_bool">Дисконт карты</label>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="row">
            <div class="col">
                Количество заявок:{{$query->clone()->count()}}
            </div>
            <div class="col">
                Количество нал:{{$query->clone()->whereJsonContains('payments', ['PaymentType' => 0])->count()}}
            </div>
            <div class="col">
                Количество безнал:{{$query->clone()->whereJsonContains('payments', ['PaymentType' => 1])->count()}}
            </div>
            <div class="col">
                Сумма нал: <span class="price">{{$query->clone()->whereJsonContains('payments', ['PaymentType' => 0])->sum('total_price')}}</span>
            </div>
            <div class="col">
                Сумма безнал: <span class="price">{{$query->clone()->whereJsonContains('payments', ['PaymentType' => 1])->sum('total_price')}}</span>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover text-nowrap table-responsive">
                <thead>
                <tr>
                    <th>ID</th>
                    <th></th>
                    <th>Покупатель</th>
{{--                    <th>Контрагент</th>--}}
{{--                    <th>Контрагент(BIN)</th>--}}
                    <th>ТТ</th>
                    <th>Признак</th>
                    <th>Статус</th>
                    <th>Продавец</th>
                    <th>тип оплаты</th>
                    <th>сумма</th>
                    <th>сдача</th>
                    <th>скидка</th>
                    <th>кэшбек</th>
                    <th>чек</th>
                    <th>Дата создание</th>
                    <th>номер дисконт карты</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    @if($order->deleted_at)
                        <tr class="bg-red">
                    @elseif($order->removed_at)
                        <tr class="bg-black">
                    @else
                        <tr>
                            @endif
                            <td>{{$order->id}}
                            </td>
                            <td class="project-actions text-left">
                                <a class="btn btn-primary btn-sm" href="{{route('admin.order.show',$order->id)}}">
                                    <i class="fas fa-folder">
                                    </i>
                                </a>
                                {{--                                    <a class="btn btn-info btn-sm" href="{{route('admin.order.edit',$order->id)}}">--}}
                                {{--                                        <i class="fas fa-pencil-alt">--}}
                                {{--                                        </i>--}}
                                {{--                                    </a>--}}

                            </td>
                            <td>{{ $order->counteragent_id ? 'Юр':'физ'  }}</td>

{{--                            <td>--}}
{{--                                @if($order->store?->counteragent_id)--}}
{{--                                    {{$order->store->counteragent->name}}--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                @if($order->store?->counteragent_id)--}}
{{--                                    {{$order->store?->counteragent?->bin}}--}}
{{--                                @endif--}}
{{--                            </td>--}}
                            <td>
                                <a href="{{route('admin.store.show',$order->store_id)}}">{{$order->store?->name}}</a>
                            </td>
                            <td>{{$order->online_sale ? 'онлайн' : 'офлайн' }}</td>
                            <td>{{$order->status}}</td>
                            <td>
                                <a href="{{route('admin.user.show',$order->user_id)}}">{{$order->user->name}}</a>
                            </td>
                            <td>{{$order->paymentTypeInfo()}}</td>
                            <td class="price">{{$order->total_price}}</td>
                            <td class="price">{{$order->give_price}}</td>

                            <td class="price">{{$order->total_discount_price}}</td>
                            <td class="price">{{$order->discount_cashback}}</td>
                            <td >
                                @if($order->ticket_print_url)
                                    <a href="{{$order->ticket_print_url}}">{{$order->check_number}}</a>
                                @endif
                            </td>

                            <td>{{$order->created_at}}</td>
                            <td>{{$order->discount_phone}}</td>

                        </tr>
                        @endforeach
                </tbody>
            </table>

        </div>

    </div>
    {{$orders->links()}}
</div>
