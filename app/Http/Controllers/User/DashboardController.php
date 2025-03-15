<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::where('created_by', Auth::user()->id)->with('order_items.product')->get();
            return Inertia::render(
                'Dashboard',
                [
                    'orders' => $orders,
                ]
            );
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
