<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

global $my_global_variable;
class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = $request->user();
            $carts = $request->carts;
            $products = $request->products;
            $mergedata = [];

            foreach ($carts as $cartitem) {
                foreach ($products as $product) {
                    if ($cartitem['product_id'] == $product['id']) {
                        $mergedata[] = array_merge($cartitem, ["title" => $product["title"], "price" => $product["price"]]);
                    }
                }
            }

            //stripe payment
            $stripe = new \Stripe\StripeClient('sk_test_51OP0BaFe5VM9PJHsgqHO5YFS5ql9hF936uj5lG1O9r8cXkpwXt4CKDUFLglR9VPQemuHDeiqpVQ9GLEOJUskaa3l00xr2ZzbJR');
            $line_items = [];
            foreach ($mergedata as $item) {
                $line_items[] =
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $item['title'],
                            ],
                            'unit_amount' => (int)($item['price'] * 100),
                        ],
                        'quantity' => $item['quantity'],
                    ];
            }



            $checkout_session = $stripe->checkout->sessions->create([
                'line_items' => $line_items,
                'mode' => 'payment',
                'success_url' => route('checkout.success', ['total' => $request->total]) . '&&session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
            ]);
            $newaddress = $request->address;
            if ($newaddress['address1'] != null) {
                $address = UserAddress::where('isMain', 1)->count();
                if ($address > 0) {
                    $address = UserAddress::where('isMain', 1)->update(['isMain' => 0]);
                }
                $address = new UserAddress();
                $address->address1 = $newaddress['address1'];
                $address->state = $newaddress['state'];
                $address->zipcode = $newaddress['zipcode'];
                $address->city = $newaddress['city'];
                $address->country_code = $newaddress['country_code'];
                $address->type = $newaddress['type'];
                $address->user_id = Auth::user()->id;
                $address->save();
            }

            return Inertia::location($checkout_session->url);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public static function success(Request $request)
    {
        try {
            $user = $request->user();
            \Stripe\Stripe::setApiKey('sk_test_51OP0BaFe5VM9PJHsgqHO5YFS5ql9hF936uj5lG1O9r8cXkpwXt4CKDUFLglR9VPQemuHDeiqpVQ9GLEOJUskaa3l00xr2ZzbJR');
            $sessionid = $request->get('session_id');
            $mainaddress = $user->user_address()->where('isMain', 1)->first();
            if ($mainaddress) {
                $order = new Order();
                $order->status = 'unpaid';
                $order->total_price = $request->total;
                $order->session_id = $sessionid;
                $order->created_by = $user->id;
                $order->user_address_id = $mainaddress->id;
                $order->save();
                $cartitems = CartItem::where(['user_id' => $user->id])->get();
                foreach ($cartitems as $cartitem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartitem->product_id,
                        'quantity' => $cartitem->quantity,
                        'unit_price' => $cartitem->product->price,
                    ]);
                    $cartitem->delete();
                    //remove cartitem from cookie
                    $cartitems_cookie = Cart::getcookiecartitems();
                    foreach ($cartitems_cookie as $item) {
                        unset($item);
                    }
                    array_splice($cartitems_cookie, 0, count($cartitems_cookie));
                    Cart::setcookiecartitems($cartitems_cookie);
                }

                $paymentdata = [
                    'order_id' => $order->id,
                    'amount' => $request->total,
                    'status' => 'pending',
                    'type' => 'stripe',
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ];

                Payment::create($paymentdata);
            }
            try {
                $checkout_session = \Stripe\Checkout\Session::retrieve($sessionid);
                if (!$checkout_session) {
                    throw new NotFoundHttpException();
                }
                $order = Order::where('session_id', $checkout_session->id)->first();
                if (!$order) {
                    throw new NotFoundHttpException();
                }
                if ($order->status == 'unpaid') {
                    $order->status = 'paid';
                    $order->save();
                }
                return redirect()->route('dashboard');
            } catch (\Exception $e) {
                throw new NotFoundHttpException();
            }
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
    public function cancel()
    {
        try {
            return redirect()->route('cart.view')->with("payment is canceled");
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
