<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Productimage;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function index()
    {
        try {
            $products = Product::with('product_images')->get();
            $productimages = Productimage::get();
            $categories = Category::get();
            $brands = Brand::get();
            return Inertia::render('User/Index', [
                'products' => $products,
                'productimages' => $productimages,
                'brands' => $brands,
                'categories' => $categories,
            ]);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
