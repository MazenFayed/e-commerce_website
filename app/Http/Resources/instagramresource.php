<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class instagramresource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        [$Pagedata]=$this->resource;
        return[
            'Pagedata'=> json_decode($Pagedata, true),
            //'total'=>$products->reduce(fn(?float $carry,Product $product)=>$carry+$product->price * $cartitems[$product->id]['quantity']),
            //'items' =>$cartitems,
            //'products' =>ProductResource::collection($products),
        ];
    }
}
