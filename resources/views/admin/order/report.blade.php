<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Покупатель</th>
            <th>ТТ</th>
            <th>Признак</th>
            <th>Статус</th>
            <th>Продавец</th>
            <th>Тип оплаты</th>
            <th>Сумма</th>
            <th>Скидка</th>
            <th>Дата</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->counteragent_id ? 'Юр' : 'Физ' }}</td>
                <td>{{ $order->store?->name }}</td>
                <td>{{ $order->online_sale ? 'онлайн' : 'офлайн' }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->paymentTypeInfo() }}</td>
                <td>{{ $order->total_price }}</td>
                <td>{{ $order->total_discount_price }}</td>
                <td>{{ $order->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>