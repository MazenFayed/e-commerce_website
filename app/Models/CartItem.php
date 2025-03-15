<?php

namespace App\Models;

use App\Helper\Cart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable =['user_id','quantity','product_id'];
    public function user()
    {
        return $this->belongsTo(User::class); 
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function getcount()
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->sum('quantity');
        }
    }
    public static function getcartitems()
    {
        if ($user = auth()->user()) {
            return CartItem::whereUserId($user->id)->get()->map(fn (CartItem $item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity]);
        }
        else{
            return Cart::getcookiecartitems();
        }
    }
    public static function getproductssandcartitems()
    {
        $cartitems = self::getcartitems();
        if($cartitems)
        {
            $ids = Arr::pluck($cartitems,'product_id');
            $products = Product::whereIn('id',$ids)->with('product_images')->get();
            $cartitems =Arr::keyBy($cartitems,'product_id');
            return[$products,$cartitems];
        }  
        else
        {
            return $cartitems;
        }    
    }
}
