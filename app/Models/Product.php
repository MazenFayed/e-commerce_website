<?php

namespace App\Models;

//use GuzzleHttp\Psr7\Request;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request as ClientRequest;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasSlug;
    use HasFactory;

    protected $fillable = ['id'];
    /**
     * Get the options for generating the slug.
     *
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function product_images()
    {
        return $this->hasMany(Productimage::class);
    }
    public function category()
    {
        return $this->belongsTo(category::class);
    }
    public function brand()
    {
        return $this->belongsTo(brand::class);
    }
    public function cartitems()
    {
        return $this->hasMany(CartItem::class);
    }



    public  function getinstance($id)
    {
        return Model::where('id', $id)->get();
    }
    public function dataarray(Request $request)
    {
        $cat_id = Category::where('name', $request->category)->get();
        $cat_id = $cat_id[0]->id;
        $bra_id = Brand::where('name', $request->brand)->get();
        $bra_id = $bra_id[0]->id;
        $data = [
            'id' => $request->id,
            'title' => $request->title,
            'slug' => $request->title,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
            'category_id' => $cat_id,
            'brand_id' => $bra_id,
            'category' => $request->category,
            'brand' => $request->brand,
            'created_by' => Auth::user()->id,

        ];
        return $data;
    }
    public function savedata($data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->slug = $data['slug'];
        $this->price = $data['price'];
        $this->quantity = $data['quantity'];
        $this->description = $data['description'];
        $this->category = $data['category'];
        $this->brand = $data['brand'];
        $var = Category::where('name', $data['category'])->get();
        $this->category_id = $var[0]->id;
        $var = Brand::where('name', $data['brand'])->get();
        $this->brand_id = $var[0]->id;
        $this->created_by = $data['created_by'];
        $this->save();
    }
    public function updateinstance($data, $id)
    {
        Model::whereId($id)->update($data);
    }

    //filters
    public function scopeFiltered(Builder $quary)
    {//dd(request('brands'));
        $quary
            ->when(request('admin'), function (Builder $q) {
                $q->where('created_by',Auth::user()->id);
            })
            ->when(request('brands'), function (Builder $q) {
                $q->whereIn('brand', request('brands'));
            })
            ->when(request('categories'), function (Builder $q) {
                $q->whereIn('category', request('categories'));
            })
            ->when(request('prices'), function (Builder $q) {
                $q->whereBetween('price', [
                    request('prices.from', 0),
                    request('prices.to', 100000)
                ]);
            })
            ->when(request('titles'), function (Builder $q) {
                $q->where('title', 'like', request("titles") . '%');
            })
            ->when(request('orderedByprice'), function (Builder $q) {
                $q->orderBy('price', request('orderedByprice'));
            })
            ->when(request('orderedBydate'), function (Builder $q) {
                $q->orderBy('created_at', request('orderedBydate'));
            });
    }
}
