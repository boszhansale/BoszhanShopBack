<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>торговая точка</th>
        <th>позиция</th>
        <th>продавец</th>
        <th>кол.</th>
        <th>цена</th>
        <th>сумма</th>
        <th>дата</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->store->name}}</td>
            <td>{{$order->name}}</td>
            <td>{{$order->user->name}}</td>
            <td>{{$order->count}}</td>
            <td>{{$order->price}}</td>
            <td>{{$order->all_price}}</td>
            <td>{{$order->created_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
