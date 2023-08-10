<?php

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
            $table->string('name');
            $table->double('actual_price');
            $table->double('sale_price');
            $table->bigInteger('total_stock')->default(0);
            $table->string('unit');
            $table->text('more_information');
            $table->foreignId('brand_id');
            $table->foreignId('user_id');
            $table->string('photo')->default(config('info.default_user_photo'));
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
