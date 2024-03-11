<table>
    <thead>
    <tr>
        <th>позиция</th>
        <th>артикул</th>
        <th>с склада</th>
        <th>на склад</th>
        <th>поступление</th>
        <th>продажа</th>
{{--        <th>возврат</th>--}}
        <th>возврат поставщику</th>
        <th>списание</th>
        <th>остаток</th>
    </tr>
    </thead>
    <tbody>
    @foreach($remains as $remain)
        <tr>
            <td>{{$remain->name}}</td>
            <td>{{$remain->article}}</td>
            <td>{{$remain->moving_from}}</td>
            <td>{{$remain->moving_to}}</td>
            <td>{{$remain->receipt}}</td>
            <td>{{$remain->sale}}</td>
{{--            <td>{{$remain->refund}}</td>--}}
            <td>{{$remain->refund_producer}}</td>
            <td>{{$remain->reject}}</td>
            <td>{{$remain->remains}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
