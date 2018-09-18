<?php

namespace Xpressengine\Plugins\XeroCommerce\Plugin;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Database
{
    public static function create()
    {
        Schema::create('xero_commerce_shop', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shop_name');
            $table->string('shop_eng_name');
            $table->string('logo_path')->nullable();
            $table->string('background_path')->nullable();
            $table->integer('shop_type');
            $table->integer('state_approval');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_commerce_shop_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');
            $table->string('user_id', 36);
        });

        Schema::create('xero_commerce_shop_delivery', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');
            $table->integer('delivery_company_id');
            $table->integer('delivery_fare');
            $table->integer('up_to_free');
            $table->boolean('is_default');
        });

        Schema::create('xero_commerce_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id');
            $table->integer('product_code');
            $table->string('name');
            $table->integer('original_price');
            $table->integer('sell_price');
            $table->double('discount_percentage');
            $table->integer('min_buy_count')->nullable();
            $table->integer('max_buy_count')->nullable();
            $table->text('description');
            $table->integer('state_display');
            $table->integer('state_deal');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_commerce_product_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('category_id');
        });

        Schema::create('xero_commerce_product_option_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->index();
            $table->integer('option_type');
            $table->string('name');
            $table->integer('addition_price');
            $table->integer('stock');
            $table->integer('alert_stock')->nullable();
            $table->integer('state_display');
            $table->integer('state_deal');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_commerce_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->smallInteger('code');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_commerce_cart', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->morphs('orderable');
            $table->integer('count');
            $table->timestamps();
        });

        Schema::create('xero_commerce_wish', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->integer('product_id');
            $table->integer('option_id');
            $table->timestamps();
        });

        Schema::create('xero_commerce_order_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('delivery_id');
            $table->morphs('orderable');
            $table->integer('original_price');
            $table->integer('sell_price');
            $table->integer('count');
            $table->timestamps();
        });

        Schema::create('xero_commerce_delivery', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_order_id');
            $table->integer('ship_no');
            $table->integer('company_id');
            $table->string('recv_name');
            $table->string('recv_phone');
            $table->string('recv_addr');
            $table->string('recv_addr_detail');
            $table->string('recv_msg');
            $table->timestamps();
        });

        Schema::create('xero_commerce_delivery_company', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('uri');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_commerce_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('method');
            $table->string('info');
            $table->integer('price');
            $table->integer('discount');
            $table->integer('millage');
            $table->integer('fare');
            $table->boolean('is_paid');
            $table->timestamps();
        });

        Schema::create('xero_commerce_order_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('xero_commerce_pay_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pay_id');
            $table->string('status');
            $table->timestamps();
        });
    }
}
