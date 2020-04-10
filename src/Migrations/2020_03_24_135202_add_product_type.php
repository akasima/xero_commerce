<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Xpressengine\Plugins\XeroCommerce\Models\Product;

class AddProductType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('xero_commerce_products')) {
            // 상품 테이블에 type 추가
            Schema::table('xero_commerce_products', function (Blueprint $table) {
                $table->string('type')->default(Product::$singleTableType)->after('shop_id');
            });
        }

        if (!Schema::hasTable('xero_commerce_products_revision')) {
            Schema::table('xero_commerce_products_revision', function (Blueprint $table) {
                $table->string('type')->default(Product::$singleTableType)->after('shop_id');
            });
        }


        if (!Schema::hasTable('xero_commerce_product_bundle_item')) {
            // 번들상품을 지원하기 위한 items 테이블 추가
            Schema::create('xero_commerce_product_bundle_item', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('bundle_product_id')->unsigned();
                $table->integer('product_id')->unsigned();
                $table->integer('quantity');
                $table->text('option_values');

                $table->timestamps();

                $table->index('bundle_product_id');
                $table->index('product_id');
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
        // 삭제
        Schema::table('xero_commerce_products', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        
        Schema::table('xero_commerce_products_revision', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        
        Schema::dropIfExists('xero_commerce_product_bundle_item');
    }
}
