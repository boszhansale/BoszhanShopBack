<?php

namespace App\Actions;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\ReceiptProduct;
use App\Models\Reject;
use Exception;
use Illuminate\Support\Facades\Auth;

class InventoryStoreAction
{

    /**
     * @throws Exception
     */
    public function execute($data)
    {
        $inventory =  Inventory::create(['user_id' => Auth::id(),'store_id' => Auth::user()->store_id]);
        $receiptData = [];
        $rejectData = [];
        foreach ($data['products'] as $item) {
            if ($item['remains'] < $item['count']){
                //Излишки
                $item['overage'] = $item['count'] - $item['remains'];
                $receiptData['products'] [] = [
                    'product_id' => $item['product_id'],
                    'count' => $item['overage'],
                ];

            }
            if ($item['remains'] > $item['count']){
                //Недостачи
                $item['shortage'] = $item['remains'] - $item['count'];
                $rejectData['products'] [] = [
                    'product_id' => $item['product_id'],
                    'count' => $item['shortage'],
                ];
            }
            $inventory->products()->create($item);
        }
        if (count($receiptData) > 0){
            $receiptData['storage_id'] = Auth::user()->storage_id;
            $receiptData['store_id'] = Auth::user()->store_id;
            $receiptData['organization_id'] = Auth::user()->organization_id;
            $receiptData['operation'] = 2;
            $receiptData['inventory_id'] = $inventory->id;
            $receiptData['description'] = "На основании инвентаризации №$inventory->id от ".$inventory->created_at->format('d.m.Y');

//            $receiptStoreAction->execute($receiptData);

        }
        if (count($rejectData) > 0)
        {
            $receiptData['storage_id'] = Auth::user()->storage_id;
            $receiptData['store_id'] = Auth::user()->store_id;
            $receiptData['organization_id'] = Auth::user()->organization_id;
            $receiptData['inventory_id'] = $inventory->id;
            $receiptData['description'] = "На основании инвентаризации №$inventory->id от ".$inventory->created_at->format('d.m.Y');
//            $rejectStoreAction->execute($receiptData);
        }
    }

}
