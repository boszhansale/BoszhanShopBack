<INVENTORY>
    <DOCUMENTNAME>920</DOCUMENTNAME>
    <NUMBER>{{ $inventory->id  }}-0800{{  now()->year  }}-{{  substr($inventory->user->id_1c,-4)  }}-{{  $inventory->payment_type  }}</NUMBER>
    <DATE>{{ $startDate->clone()->format('Y-m-d')  }}</DATE>
    <HEAD>
        <WAREHOUSE>{{$inventory->store->warehouse_in}}</WAREHOUSE>
        @foreach($inventory->products()->get() as $inventoryProduct)
            <POSITION>
                <POSITIONNUMBER>{{$loop->iteration}}</POSITIONNUMBER>
                <PRODUCT>{{$inventoryProduct->product_id+5000000000000}}</PRODUCT>
                @if($inventoryProduct->product->measure == 1)<ORDERUNIT>PCE</ORDERUNIT>@else<ORDERUNIT>KGM</ORDERUNIT>@endif
                <COUNT>{{$inventoryProduct->count}}</COUNT>
                <CHARACTERISTIC>
                    <DESCRIPTION>{{$inventoryProduct->product->name}}</DESCRIPTION>
                </CHARACTERISTIC>
            </POSITION>
        @endforeach
    </HEAD>
</INVENTORY>
