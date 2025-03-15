<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteUri;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductListController extends Controller
{
    /* public function index(Request $request)
    {
        dd($request->path);
        $products = Product::with('product_images');
        $categories = Category::get();
        $brands = Brand::get();
        $filterproducts = $products->filtered()->paginate(9)->withQueryString();
        //dd($filterproducts);
        return Inertia::render('User/productfilter', [
            'products' => ProductResource::collection($filterproducts),
            'brands' => $brands,
            'categories' => $categories,
        ]);
    }*/
    public function product_search(Request $request)
    {
        try {
            //dd($request->path);
            $path = $request->path;
            $products = Product::with('product_images');
            $categories = Category::get();
            $brands = Brand::get();
            $filterproducts = $products->filtered()->Paginate()->withQueryString();
            return Inertia::render($path, [
                'products' => ProductResource::collection($filterproducts),
                'brands' => $brands,
                'categories' => $categories,
            ]);
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
