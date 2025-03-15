<?php

namespace App\Http\Resources;

use App\Helper\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        [$products,$cartitems]=$this->resource;
        if($products)
        {
            return[
                'count'=>Cart::getcount(),
                'total'=>$products->reduce(fn(?float $carry,Product $product)=>$carry+$product->price * $cartitems[$product->id]['quantity']),
                'items' =>$cartitems,
                'products' =>ProductResource::collection($products),
            ];
        }
        else
        {
            return [] ;
        }      
    }
}
