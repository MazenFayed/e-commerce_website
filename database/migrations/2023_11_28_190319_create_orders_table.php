<?php

use App\Models\Category;
use App\Models\User;
use App\Models\UserAddress;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_price',20,2);
            $table->string('status',45);
            $table->string('session_id',255);
            $table->foreignIdFor(UserAddress::class)->constrained()->cascadeOnUpdate();
            $table->foreignIdFor(User::class,'created_by')->nullable()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(User::class,'updated_by')->nullable()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
