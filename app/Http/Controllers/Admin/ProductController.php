<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Productimage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use function Laravel\Prompts\alert;

class 

ProductController extends Controller
{
    /* public function index()
    { 
        $products = Product::with( 'product_images')->get();
        $brands = Brand::get();
        $categories = Category::get();
        return Inertia::render('Admin/product/index', ['products' => $products, 'brands' => $brands, 'categories' => $categories]);
    }*/

    public function store(Request $request)
    {
        try {
            //dd($request);
            $product = new Product();
            $productimages = new Productimage;
            $product->savedata($product->dataarray($request));
            if ($request->hasFile('product_images')) {
                $productimages->addimages($request->file('product_images'), $product->id);
            }
            return redirect('https://localhost/admin/products?path=Admin/product/index&admin= product created successfully')->with('success', 'product created successfully');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function deleteImage($id)
    {
        try {
            $instance = new Productimage();
            $instance->where('id', $id)->get()->each->delete();
            return redirect('https://localhost/admin/products?path=Admin/product/index&admin= image deleted successfully')->with('success', 'image deleted successfully');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function update(Request $request)
    {
        try {
            //dd($request);
            $product = new Product;
            $productimages = new Productimage;
            $product->updateinstance($product->dataarray($request), $request->id);
            if ($request->hasFile('product_images')) {
                $productimages->addimages($request->file('product_images'), $request->id);
            }
            return redirect('https://localhost/admin/products?path=Admin/product/index&admin= product updated successfully')->with('success', 'product updated successfully');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function deleteproduct($id)
    {
        try {
            $instance = new Product();
            $instance->where('id', $id)->get()->each->delete();
            return redirect('https://localhost/admin/products?path=Admin/product/index&admin= product deleted successfully')->with('success', 'product deleted successfully');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
}
