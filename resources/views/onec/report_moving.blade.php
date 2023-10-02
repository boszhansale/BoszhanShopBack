<MOVING>
    <DOCUMENTNAME>420</DOCUMENTNAME>
    <NUMBER>{{ $moving->id  }}-0800{{  now()->year  }}-{{  substr($moving->user->id_1c,-4)  }}-1</NUMBER>
    <DATE>{{ $moving->created_at->format('Y-m-d')  }}</DATE>
    <DELIVERYDATE>{{ now()->format('Y-m-d') }}</DELIVERYDATE>
    <MANAGER>{{  config('app.driver_id_onec')  }}</MANAGER>
    <DRIVER>{{$moving->user->id_1c}}</DRIVER>
    <STORE_IN>{{$moving->store_id}}</STORE_IN>
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
        @foreach($moving->products()->get() as $refundProduct)
            <POSITION>
                <POSITIONNUMBER>{{$loop->iteration}}</POSITIONNUMBER>
                <PRODUCT>{{$refundProduct->product_id+5000000000000}}</PRODUCT>
                <PRODUCTIDSUPPLIER/>
                <PRODUCTIDBUYER>{{$idSell}}</PRODUCTIDBUYER>
                <ORDEREDQUANTITY>{{number_format((float)$refundProduct->count, 3, '.', '')}}</ORDEREDQUANTITY>
                @if($refundProduct->product->measure == 1) <ORDERUNIT>PCE</ORDERUNIT>@else<ORDERUNIT>KGM</ORDERUNIT>@endif
                <ORDERPRICE>{{ round($refundProduct->price - ($refundProduct->price / 100 * 12))}}.00</ORDERPRICE>
                <PRICEWITHVAT>{{$refundProduct->price}}.00</PRICEWITHVAT>
                <VAT>12</VAT>
                <CHARACTERISTIC>
                    <DESCRIPTION>{{$refundProduct->product->name}}</DESCRIPTION>
                </CHARACTERISTIC>
            </POSITION>
        @endforeach
    </HEAD>
</MOVING>
