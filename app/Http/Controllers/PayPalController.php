<?php

namespace App\Http\Controllers;

use App\Helper\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Laravel\Prompts\alert;

class PayPalController extends Controller
{
    public function payment(Request $request)
    {
        try {
            $user = $request->user();
            $carts = $request->carts;
            $products = $request->products;
            //paypal payment
            $provider = new PayPalClient;
            $paypaltoken = $provider->getAccessToken();
            $provider->setApiCredentials(config('paypal'));
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('success', ['total' => $request->total, 'admin' => $request->admin]),
                    "cancel_url" => route('cancel')
                ],
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "USD",
                            "value" => $request->total
                        ]
                    ]
                ]
            ]);
            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $link) {
                    if ($link['rel'] == 'approve') {
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

                        return Inertia::location($link['href']);
                    }
                }
            } else {
                return redirect()->route('cancel');
            }
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
    public function success(Request $request)
    {
        try {
            $user = $request->user();
            if ($request->admin) {
                User::where('id', $user->id)->update(['isAdmin' => 1]);
                return redirect()->route('dashboard');
            }
            $provider = new PayPalClient;
            $paypaltoken = $provider->getAccessToken();
            $provider->setApiCredentials(config('paypal'));
            $response = $provider->capturePaymentOrder($request->token);
            //dd($response);
            if (isset($response['status']) && $response['status'] == 'COMPLETED') {
                $mainaddress = $user->user_address()->where('isMain', 1)->first();
                if ($mainaddress) {
                    $order = new Order();
                    $order->status = 'unpaid';
                    $order->total_price = $request->total;
                    $order->session_id = $response['id'];
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
                        'type' => 'paypal',
                        'created_by' => $user->id,
                        'updated_by' => $user->id,
                    ];

                    Payment::create($paymentdata);
                }
                try {
                    $order = Order::where('session_id', $response['id'])->first();
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
            } else {
                return redirect()->route('cancel');
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
