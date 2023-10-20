<INVENTORY>
    <DOCUMENTNAME>920</DOCUMENTNAME>
    <NUMBER>{{ $inventory->id  }}-0800{{  now()->year  }}-{{  substr($inventory->user->id_1c,-4)  }}-{{  $inventory->payment_type  }}</NUMBER>
    <DATE>{{ $startDate->clone()->format('Y-m-d')  }}</DATE>
    <DELIVERYDATE>{{ $startDate->clone()->format('Y-m-d') }}</DELIVERYDATE>
    <MANAGER>{{  config('app.driver_id_onec')  }}</MANAGER>
    <DRIVER>{{$inventory->user->id_1c}}</DRIVER>
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
        @foreach($inventory->products()->get() as $inventoryProduct)
            <POSITION>
                <POSITIONNUMBER>{{$loop->iteration}}</POSITIONNUMBER>
                <PRODUCT>{{$inventoryProduct->product_id+5000000000000}}</PRODUCT>
                <PRODUCTIDSUPPLIER/>
                <PRODUCTIDBUYER>{{$idSell}}</PRODUCTIDBUYER>
                <ORDEREDQUANTITY>{{number_format((float)$inventoryProduct->count, 3, '.', '')}}</ORDEREDQUANTITY>
                @if($inventoryProduct->product->measure == 1) <ORDERUNIT>PCE</ORDERUNIT>@else<ORDERUNIT>KGM</ORDERUNIT>@endif
                <ORDERPRICE>{{ round($inventoryProduct->price - ($inventoryProduct->price / 100 * 12))}}.00</ORDERPRICE>
                <PRICEWITHVAT>{{$inventoryProduct->price}}.00</PRICEWITHVAT>
                <RECEIPT>{{$inventoryProduct->receipt}}</RECEIPT>
                <SALE>{{$inventoryProduct->sale}}</SALE>
                <MOVING>{{$inventoryProduct->moving}}</MOVING>
                <REMAINS>{{$inventoryProduct->remains}}</REMAINS>
                <OVERAGE>{{$inventoryProduct->overage}}</OVERAGE>
                <OVERAGE_PRICE>{{$inventoryProduct->overage_price}}</OVERAGE_PRICE>
                <SHORTAGE>{{$inventoryProduct->shortage}}</SHORTAGE>
                <SHORTAGE_PRICE>{{$inventoryProduct->shortage_price}}</SHORTAGE_PRICE>
                <VAT>12</VAT>
                <CHARACTERISTIC>
                    <DESCRIPTION>{{$inventoryProduct->product->name}}</DESCRIPTION>
                </CHARACTERISTIC>
            </POSITION>
        @endforeach
    </HEAD>
</INVENTORY>
