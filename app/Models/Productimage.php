<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Productimage extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'image',];


    function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function addimages($productImages , $id)
    {
        foreach ($productImages as $image) {
            $uniqueName = time() . '-' . Str::random(10). '.' . $image->getClientOriginalExtension();
            $image->move('product_images', $uniqueName);
            Productimage::create([
                'product_id' => $id,
                'image' => 'product_images/' . $uniqueName,
            ]);
        }
    }
}
