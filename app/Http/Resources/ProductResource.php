<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category,
            'id_1c' => $this->id_1c,
            'article' => $this->article,
            'measure' => $this->measure,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'barcodes' => $this->barcodes,
            'remainder' => $this->remainder,
            'enabled' => $this->enabled,
            'purchase' => $this->purchase,
            'return' => $this->return,
            'presale_id' => $this->presale_id,
            'discount' => $this->discount,
            'hit' => $this->hit,
            'new' => $this->new,
            'action' => $this->action,
            'discount_5' => $this->discount_5,
            'discount_10' => $this->discount_10,
            'discount_15' => $this->discount_15,
            'discount_20' => $this->discount_20,
            'rating' => $this->rating,
            'price_type_id' => 3,
            'price' => $this->prices()->wherePriceTypeId(3)->first()->price,
            'discount_price' => ceil($this->getDiscountPrice(0,0)),
            'images' => $this->images,
        ];
    }
}
