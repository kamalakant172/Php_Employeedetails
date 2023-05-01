<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('category_products')){
            Schema::create('category_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBiginteger('product_id')->unsigned();
                $table->unsignedBiginteger('category_id')->unsigned();

                $table->foreign('product_id')->constrained('products');
                $table->foreign('category_id')->constrained('categories');
                $table->timestamps();
            });
        }    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_products');
    }
};
