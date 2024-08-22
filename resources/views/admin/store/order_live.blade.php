<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <input wire:model="search" type="search" name="search" placeholder="поиск" class="form-control">
                </div>
                <div class="col-md-1">
                    <select wire:model="status_id" class="form-control">
                        <option value="all">Все статусы</option>
                        @foreach($statuses as $status)
                            <option value="{{$status->id}}">{{$status->description}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <input wire:model="start_date"  type="date" class="form-control">
                    <input wire:model="end_date"  type="date" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row justify-content-between">
                <div class="col">
                    <h5>Общая:</h5>
                    <ul>
                        <li>кол. заявок : {{$query->clone()->count()}}</li>
                        <li>кол. закрытых : {{$query->clone()->whereNotNull('delivered_date')->count()}}</li>
                        <li>сумма заявок: <span class="price">{{$query->clone()->sum('orders.purchase_price')}}</span>
                        </li>
                        <li>кол. возврат: {{$query->clone()->where('orders.return_price', '>', 0)->count()}}</li>
                        <li>сумма. возврат: <span class="price">{{$query->clone()->sum('orders.return_price')}}</span>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h5>Юр лицо</h5>
                    <ul>
                        <li>кол. заявок: {{$query->clone()->whereNotNull('stores.counteragent_id')->count()}}</li>
                        <li>кол. закрытых
                            : {{$query->clone()->whereNotNull('stores.counteragent_id')->whereNotNull('delivered_date')->count()}}</li>
                        <li>сумма заявок: <span
                                class="price">{{$query->clone()->whereNotNull('stores.counteragent_id')->sum('orders.purchase_price')}}</span>
                        </li>
                        <li>кол.
                            возврат: {{$query->clone()->whereNotNull('stores.counteragent_id')->where('orders.return_price', '>', 0)->count()}}</li>
                        <li>сумма. возврат: <span
                                class="price">{{$query->clone()->whereNotNull('stores.counteragent_id')->sum('orders.return_price')}}</span>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h5>Физ лицо</h5>
                    <ul>
                        <li>кол. заявок: {{$query->clone()->whereNull('stores.counteragent_id')->count()}}</li>
                        <li>кол. закрытых
                            : {{$query->clone()->whereNull('stores.counteragent_id')->whereNotNull('delivered_date')->count()}}</li>
                        <li>сумма заявок: <span
                                class="price">{{$query->clone()->whereNull('stores.counteragent_id')->sum('orders.purchase_price')}}</span>
                        </li>
                        <li>кол.
                            возврат: {{$query->clone()->whereNull('stores.counteragent_id')->where('orders.return_price', '>', 0)->count()}}</li>
                        <li>сумма. возврат: <span
                                class="price">{{$query->clone()->whereNull('stores.counteragent_id')->sum('orders.return_price')}}</span>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <ul>
                        @if($order_return_price > 0)
                            @if(($order_return_price / $order_purchase_price)*100 >= 60 )
                                <li style="color: red">процент
                                    возврата: {{ round(($order_return_price / $order_purchase_price)*100)  }} %
                                </li>
                            @else
                                <li>процент возврата: {{ round(($order_return_price / $order_purchase_price)*100)  }}%
                                </li>
                            @endif

                        @else
                            <li>процент возврата: 0%</li>
                        @endif
                    </ul>

                </div>

            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover text-nowrap table-responsive">
                <thead>
                <tr>
                    <th>ID</th>
                    <th></th>
                    <th>Контрагент</th>
                    <th>ТТ</th>
                    <th>Статус</th>
                    <th>Торговый</th>
                    <th>Водитель</th>
                    <th>сумма</th>
                    <th>возврат</th>
                    <th>Дата создание</th>
                    <th>Дата доставки</th>
                    <th>тип оплаты</th>
                    <th>статус оплаты</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{$order->id}}</td>
                        <td  class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="{{route('admin.order.show',$order->id)}}">
                                <i class="fas fa-folder">
                                </i>

                            </a>
                            @if(Auth::user()->permissionExists('order_edit'))
                                <a class="btn btn-info btn-sm" href="{{route('admin.order.edit',$order->id)}}">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </a>
                            @endif
                            @if(Auth::user()->permissionExists('order_delete'))
                                <a  class="btn btn-danger btn-sm" href="{{route('admin.order.delete',$order->id)}}" onclick="return confirm('Удалить?')">
                                    <i class="fas fa-trash"></i>

                                </a>
                            @endif
                        </td>
                        <td>
                            @if($order->store->counteragent)
                                <a href="{{route('admin.counteragent.show',$order->store->counteragent_id)}}">{{$order->store->counteragent->name}}</a>
                            @endif
                        </td>
                        <td><a href="{{route('admin.store.show',$order->store_id)}}">{{$order->store->name}}</a></td>
                        <td>{{$order->status->description}}</td>
                        <td><a href="{{route('admin.user.show',$order->salesrep_id)}}">{{$order->salesrep->name}}</a></td>
                        <td><a href="{{route('admin.user.show',$order->driver_id)}}">{{$order->driver->name}}</a></td>
                        <td class="price">{{$order->purchase_price}}</td>
                        <td class="price">{{$order->return_price}}</td>
                        <td>{{$order->created_at}}</td>
                        <td>{{$order->delivery_date}}</td>
                        <td>{{$order->paymentType->name}}</td>
                        <td>{{$order->paymentStatus->name}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
    {{$orders->links()}}
</div>
