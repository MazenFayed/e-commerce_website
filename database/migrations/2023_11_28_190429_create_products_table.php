<?php

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title',200);
            $table->string('slug',400);
            $table->integer('quantity');
            $table->longText('description')->nullable();
            $table->boolean('instock')->default(0);
            $table->boolean('published')->default(0);
            $table->decimal('price',10,2);
            $table->foreignIdFor(User::class ,'created_by')->nullable()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(User::class ,'updated_by')->nullable()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Brand::class ,'brand_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            //$table->foreignId('brand')->references('name')->on('Brand');
           // $table->foreignId('category')->references('name')->on('Category');
            $table->foreignIdFor(Category::class ,'category_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('category',200)->nullable();
            $table->string('brand',200)->nullable();
            $table->foreignIdFor(User::class ,'deleted_by')->nullable()->cascadeOnUpdate()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
