<?php

namespace App\Helper;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class Cart
{
    public static function getcount()
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->count();
        }
        else
        {
            return array_reduce(self::getcookiecartitems(),fn($carry  )=>$carry+1,0);
        }
    }
    public static function getcartitems()
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->get()->map(fn (CartItem $item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]);
        }
        else{
            return self::getcookiecartitems();
        }
    }
    public static function getcookiecartitems()
    {
        return json_decode(request()->cookie('cart_items', '[]'), true);
       
          //  $cookies = explode('; ', $_COOKIE["cart_items"]);
            //return $cookies;
          
    }
    //(fn (int $carry, array $item) => $carry + $item['quantity']), 
    public static function setcookiecartitems(array $cartitems)
    {
        Cookie::queue('cart_items',json_encode($cartitems),60*24*30);
    }
    public static function savecookiecartitems()
    {
        $user = auth()->user();
        $usercartitems = CartItem::where(['user_id' => $user->id])->get()->keyBy('product_id');
        $savedcartitems = [];
        foreach (self::getcookiecartitems() as $cartitem) {
            if (isset($usercartitems[$cartitem['product_id']])) {
                $usercartitems[$cartitem['product_id']]->update(['quantity' => $cartitem['quantity']]);
                continue;
            }
            $savedcartitems[] =
                [
                    'user_id' => $user->id,
                    'product_id' => $cartitem['product_id'],
                    'quantity' => $cartitem['quantity']
                ];
        }
        if (!empty($savedcartitems)) {
            CartItem::insert($savedcartitems);
        }
    }

    public static function movecartitemintodb()
    {
        $request = request();
        $cartitems = self::getcookiecartitems();
        $newcartitems = [];
        foreach ($cartitems as $cartitem) {
            $existingcartitem = CartItem::where([
                'user_id' => $request->user()->id,
                'product_id' => $cartitem['product_id'],
            ])->first();

            if (!$existingcartitem) {
                $newcartitems[] = [
                    'user_id' => $request->user()->id,
                    'product_id' => $cartitem['product_id'],
                    'quantity' => $cartitem['quantity'],
                ];
            }
        }
        if (!empty($newcartitems)) {
            CartItem::insert($newcartitems);
        }
    }

    public static function getproductssandcartitems()
    {
        $cartitems = self::getcartitems();
            $ids = Arr::pluck($cartitems,'product_id');
            $products = Product::whereIn('id',$ids)->with('product_images')->get();
            $cartitems =Arr::keyBy($cartitems,'product_id');
            return[$products,$cartitems];
        
    }
}
