<REJECT>
    <DOCUMENTNAME>720</DOCUMENTNAME>
    <NUMBER>{{ $reject->id  }}-0800{{  now()->year  }}-{{  substr($reject->user->id_1c,-4)  }}-{{  $reject->payment_type  }}</NUMBER>
    <DATE>{{ $reject->created_at->format('Y-m-d')  }}</DATE>
    <DELIVERYDATE>{{ now()->format('Y-m-d') }}</DELIVERYDATE>
    <MANAGER>{{  config('app.driver_id_onec')  }}</MANAGER>
    <DRIVER>{{$reject->user->id_1c}}</DRIVER>
    <CURRENCY>KZT</CURRENCY>
    <HEAD>
        <SUPPLIER>9864232489962</SUPPLIER>
        <BUYER>{{$idOnec}}</BUYER>
        <DELIVERYPLACE>{{$idSell}}</DELIVERYPLACE>
        <INVOICEPARTNER>{{$idOnec}}</INVOICEPARTNER>
        <SENDER>{{$idSell}}</SENDER>
        <RECIPIENT>9864232489962</RECIPIENT>
        <EDIINTERCHANGEID>0</EDIINTERCHANGEID>
        <FINALRECIPIENT>{{$idSell}}</FINALRECIPIENT>
        @foreach($reject->products()->get() as $orderProduct)
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
</REJECT>
