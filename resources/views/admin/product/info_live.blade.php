<div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <labe>бренд</labe>
                        <select wire:model.live="brand_id" class="form-control">
                            <option value=""></option>
                            @foreach($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <labe>категория</labe>
                        <select wire:model.live="category_id" class="form-control">
                            <option value=""></option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <labe>продукт</labe>
                        <select wire:model.live="product_id" class="form-control">
                            <option value=""></option>
                            @foreach($products as $product)
                                <option value="{{$product->id}}">{{$product->article}} {{$product->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>магазин</label>
                        <select wire:model.live="store_id" class="form-control">
                            <option value=""></option>
                            @foreach($stores as $store)
                                <option value="{{$store->id}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>продавец</label>
                        <select wire:model.live="user_id" class="form-control">
                            <option value=""></option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->id}} {{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($orderInfo)
        <a class="btn btn-primary" data-toggle="collapse" href="#orders" role="button" aria-expanded="false"
           aria-controls="orders">
            Продажа
            <span>количество: {{$orderInfo?->total_count}} </span>
            <span>сумма: {{$orderInfo?->total_price}} </span>
        </a>
    @endif
    <div class="collapse multi-collapse" id="orders">
        <div class="card">
            <div class="card-header">
                Продажа
            </div>
            <div class=" card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>номер заказа</th>
                        <th>дата</th>
                        <th>магазин</th>
                        <th>продавец</th>
                        <th>кол.</th>
                        <th>цена</th>
                        <th>скидка</th>
                        <th>сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><a href="{{route('admin.order.show',$order->id)}}" target="_blank">{{$order->id}}</a></td>
                            <td>{{$order->created_at}}</td>
                            <td>{{$order->store_name}}</td>
                            <td>{{$order->user_name}}</td>
                            <td>{{$order->count}}</td>
                            <td>{{$order->price}}</td>
                            <td>{{$order->discount_price}}</td>
                            <td>{{$order->all_price}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @if(count($refunds) > 0)
        <a class="btn btn-primary" data-toggle="collapse" href="#refunds" role="button" aria-expanded="false"
           aria-controls="refunds">
            Возвраты
        </a>
    @endif
    <div class="collapse multi-collapse" id="refunds">
        <div class="card">
            <div class="card-header">
                Возвраты
            </div>
            <div class=" card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>номер</th>
                        <th>дата</th>
                        <th>магазин</th>
                        <th>продавец</th>
                        <th>кол.</th>
                        <th>цена</th>
                        <th>сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($refunds as $refund)
                        <tr>
                            <td><a href="{{route('admin.refund.show',$refund->id)}}" target="_blank">{{$refund->id}}</a></td>
                            <td>{{$refund->created_at}}</td>
                            <td>{{$refund->store_name}}</td>
                            <td>{{$refund->user_name}}</td>
                            <td>{{$refund->count}}</td>
                            <td>{{$refund->price}}</td>
                            <td>{{$refund->all_price}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @if(count($receipts) > 0)
        <a class="btn btn-primary" data-toggle="collapse" href="#receipts" role="button" aria-expanded="false"
           aria-controls="receipts">
            Поступление
        </a>
    @endif
    <div class="collapse multi-collapse" id="receipts">
        <div class="card">
            <div class="card-header">
                Поступление
            </div>
            <div class=" card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>номер</th>
                        <th>дата</th>
                        <th>магазин</th>
                        <th>продавец</th>
                        <th>кол.</th>
                        <th>цена</th>
                        <th>сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($receipts as $receipt)
                        <tr>
                            <td><a href="{{route('admin.receipt.show',$receipt->id)}}" target="_blank">{{$receipt->id}}</a></td>
                            <td>{{$receipt->created_at}}</td>
                            <td>{{$receipt->store_name}}</td>
                            <td>{{$receipt->user_name}}</td>
                            <td>{{$receipt->count}}</td>
                            <td>{{$receipt->price}}</td>
                            <td>{{$receipt->all_price}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @if(count($inventories) > 0)
        <a class="btn btn-primary" data-toggle="collapse" href="#inventories" role="button" aria-expanded="false"
           aria-controls="inventories">инвентаризация</a>
    @endif
    <div class="collapse multi-collapse" id="inventories">
        <div class="card">
            <div class="card-header">
                инвентаризация
            </div>
            <div class=" card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>инвентаризация</th>
                        <th>дата</th>
                        <th>магазин</th>
                        <th>продавец</th>
                        <th>Цена</th>
                        <th>факт кол</th>
                        <th>кол</th>
                        <th>поступления</th>
                        <th>продажа</th>
                        <th>Недостача</th>
                        <th>излишки</th>
                        <th>с склада</th>
                        <th>на склада</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($inventories as $inventory)
                        <tr>
                            <td><a target="_blank" href="{{route('admin.inventory.show',$inventory->id)}}">{{$inventory->id}}</a></td>
                            <td>{{$inventory->created_at}}</td>
                            <td>{{$inventory->store_name}}</td>
                            <td>{{$inventory->user_name}}</td>
                            <td>{{$inventory->price}}</td>
                            <td>{{$inventory->count}}</td>
                            <td>{{$inventory->remains}}</td>
                            <td>{{$inventory->between_receipt}}</td>
                            <td>{{$inventory->between_sale}}</td>
                            <td>{{$inventory->shortage}}</td>
                            <td>{{$inventory->overage}}</td>
                            <td>{{$inventory->between_moving_from}}</td>
                            <td>{{$inventory->between_moving_to}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @if(count($movingFrom) > 0)
        <a class="btn btn-primary" data-toggle="collapse" href="#movingFrom" role="button" aria-expanded="false"
           aria-controls="movingFrom">перемещение с склада</a>
    @endif
    <div class="collapse multi-collapse" id="movingFrom">
        <div class="card">
            <div class="card-header">
                перемещение с склада
            </div>
            <div class=" card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>перемещение</th>
                        <th>дата</th>
                        <th>магазин</th>
                        <th>продавец</th>
                        <th>Цена</th>
                        <th>кол</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($movingFrom as $moving)
                        <tr>
                            <td><a target="_blank" href="{{route('admin.moving.show',$moving->id)}}">{{$moving->id}}</a></td>
                            <td>{{$moving->created_at}}</td>
                            <td>{{$moving->store_name}}</td>
                            <td>{{$moving->user_name}}</td>
                            <td>{{$moving->price}}</td>
                            <td>{{$moving->count}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    @if(count($movingTo) > 0)
        <a class="btn btn-primary" data-toggle="collapse" href="#movingTo" role="button" aria-expanded="false"
           aria-controls="movingTo">перемещение на склада</a>
    @endif
    <div class="collapse multi-collapse" id="movingTo">
        <div class="card">
            <div class="card-header">
                перемещение на склада
            </div>
            <div class=" card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>перемещение</th>
                        <th>дата</th>
                        <th>магазин</th>
                        <th>продавец</th>
                        <th>Цена</th>
                        <th>кол</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($movingTo as $moving)
                        <tr>
                            <td><a target="_blank" href="{{route('admin.moving.show',$moving->id)}}">{{$moving->id}}</a></td>
                            <td>{{$moving->created_at}}</td>
                            <td>{{$moving->store_name}}</td>
                            <td>{{$moving->user_name}}</td>
                            <td>{{$moving->price}}</td>
                            <td>{{$moving->count}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

