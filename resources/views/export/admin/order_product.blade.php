<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>торговая точка</th>
        <th>продавец</th>
        <th>артикул</th>
        <th>позиция</th>
        <th>ед.</th>
        <th>кол.</th>
        <th>цена</th>
        <th>сумма</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{$order->product_id}}</td>
            <td>{{$order->store->name}}</td>
            <td>{{$order->user->name}}</td>
            <td>{{$order->article}}</td>
            <td>{{$order->name}}</td>
            <td>{{$order->measure == 1 ?'шт':'кг'}}</td>
            <td>{{$order->count}}</td>
            <td>{{$order->price}}</td>
            <td>{{$order->all_price}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
