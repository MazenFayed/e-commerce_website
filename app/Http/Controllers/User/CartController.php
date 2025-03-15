<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helper\Cart;
use App\Http\Resources\CartResource;
use App\Models\UserAddress;
use Inertia\Inertia;
use PhpParser\Node\Stmt\Foreach_;
use Psy\Readline\Hoa\Console;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    public function view(Request $request, Product $product)
    {
        try {
            $user = $request->user();
            if ($user) {
                $cartitems = CartItem::where('user_id', $user->id)->get();
                $useraddress = UserAddress::where('user_id', $user->id)->where('isMain', 1)->first();
                if ($cartitems->count() > 0) {
                    return Inertia::render('User/cartlist', [
                        'cartitems' => $cartitems,
                        'useraddress' => $useraddress,
                    ]);
                }
            } else {
                $cartitems = Cart::getcookiecartitems();
                if (count($cartitems) > 0) {
                    $cartitems = new CartResource(Cart::getproductssandcartitems());
                    return Inertia::render('User/cartlist', [
                        'cartitems' => $cartitems,
                    ]);
                } else {
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
    public function store(Request $request, Product $product)
    {
        try {
            $quantity = $request->post('quantity', 1);
            $user = $request->user();

            if ($user) {
                $cartitem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
                if ($cartitem) {
                    $cartitem->increment('quantity');
                } else {
                    CartItem::create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'quantity' => 1,
                    ]);
                }
            } else {
                $cartitems = Cart::getcookiecartitems();
                $isproductexists = false;
                /*foreach ($cartitems as $item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    $isproductexists = true;
                    break;
                }
            }*/

                for ($i = 0; $i < count($cartitems); $i++) {
                    if ($cartitems[$i]['product_id'] == $product->id) {
                        $cartitems[$i]['quantity'] += $quantity;
                        $isproductexists = true;
                        break;
                    }
                }

                if (!$isproductexists) {
                    $cartitems[] = [
                        'user_id' => null,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                    ];
                }
                //dd($cartitems);
                Cart::setcookiecartitems($cartitems);
            }
            return redirect()->back()->with('success', 'cart added successfully');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
    public function update(Request $request, Product $product)
    {
        try {
            $quantity = $request->integer('quantity');
            $user = $request->user();
            if ($user) {
                CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);
            } else {
                $cartitems = Cart::getcookiecartitems();
                /*foreach ($cartitems as $item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    $isproductexists = true;
                    break;
                }
            }*/

                for ($i = 0; $i < count($cartitems); $i++) {
                    if ($cartitems[$i]['product_id'] == $product->id) {
                        $cartitems[$i]['quantity'] = $quantity;
                        $isproductexists = true;
                        break;
                    }
                }
                Cart::setcookiecartitems($cartitems);
            }
            return redirect()->back();
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
    public function delete(Request $request, Product $product)
    {
        try {
            $user = $request->user();
            if ($user) {
                CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();
                if (CartItem::where(['user_id' => $user->id])->count() <= 0) {
                    return redirect()->route('user.home')->with('info', 'your cart is empty');
                } else {
                    return redirect()->back()->with('success', 'item removed successfully');
                }
            } else {
                $cartitems = Cart::getcookiecartitems();
                foreach ($cartitems as $i => &$item) {
                    if ($item['product_id'] === $product->id) {
                        array_splice($cartitems, $i, 1);
                        break;
                    }
                }
                Cart::setcookiecartitems($cartitems);
                if (count($cartitems) <= 0) {
                    return redirect()->route('user.home')->with('info', 'your cart is empty');
                } else {
                    return redirect()->back()->with('success', 'item removed successfully');
                }
            }
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
