<ORDER>
    <DOCUMENTNAME>620</DOCUMENTNAME>
    <NUMBER>{{ $store->id  }}-0800{{  now()->format('Ymd')}}</NUMBER>
    <DATE>{{ $startDate->clone()->format('Y-m-d')  }}</DATE>
    <STOREIN>{{$store->warehouse_in}}</STOREIN>
    <CURRENCY>KZT</CURRENCY>
    <HEAD>
        <STOREIN>{{$store->warehouse_in}}</STOREIN>
        <SUPPLIER>9864232489962</SUPPLIER>
        <SENDER>300000000000004</SENDER>
        <BUYER>000-007034</BUYER>
        @foreach($orderProducts as $orderProduct)
            <POSITION>
                <POSITIONNUMBER>{{$loop->iteration}}</POSITIONNUMBER>
                <PRODUCT>{{$orderProduct->product_id+5000000000000}}</PRODUCT>
                <PRODUCTIDSUPPLIER/>
                <PRODUCTIDBUYER>{{$idSell}}</PRODUCTIDBUYER>
                <ORDEREDQUANTITY>{{number_format((float)$orderProduct->count, 3, '.', '')}}</ORDEREDQUANTITY>
                @if($orderProduct->product->measure == 1) <ORDERUNIT>PCE</ORDERUNIT>@else<ORDERUNIT>KGM</ORDERUNIT>@endif
                <ORDERPRICE>{{ round($orderProduct->price - ($orderProduct->price / 100 * 12))}}.00</ORDERPRICE>
                <PRICEWITHVAT>{{$orderProduct->price}}.00</PRICEWITHVAT>
                <VAT>12</VAT>
                <CHARACTERISTIC>
                    <DESCRIPTION>{{$orderProduct->product->name}}</DESCRIPTION>
                </CHARACTERISTIC>
            </POSITION>
        @endforeach
    </HEAD>
</ORDER>
