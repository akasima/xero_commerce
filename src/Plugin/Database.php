<?php

namespace Xpressengine\Plugins\XeroStore\Plugin;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Database
{
    public static function create()
    {
        Schema::create('xero_store_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_code');
            $table->integer('first_category_id');
            $table->integer('second_category_id');
            $table->integer('third_category_id');
            $table->string('name');
            $table->integer('price');
            $table->integer('min_buy_count')->nullable();
            $table->integer('max_buy_count')->nullable();
            $table->text('description');
            $table->integer('state_display');
            $table->integer('state_deal');
            $table->timestamps();
        });

        Schema::create('xero_store_product_option_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('option_type');
            $table->string('name');
            $table->integer('addition_price');
            $table->integer('stock');
            $table->integer('alert_stock');
            $table->integer('state_display');
            $table->integer('state_deal');
        });

        Schema::create('xero_store_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->integer('pay_id');
            $table->smallInteger('code');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_store_cart', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->integer('product_id');
            $table->integer('option_id');
            $table->timestamps();
        });

        Schema::create('xero_store_wish', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->integer('product_id');
            $table->integer('option_id');
            $table->timestamps();
        });

        Schema::create('xero_store_option_order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('product_id');
            $table->integer('option_id');
            $table->integer('delivery_id');
            $table->timestamps();
        });

        Schema::create('xero_store_delivery', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ship_no');
            $table->integer('company_id');
            $table->string('recv_name');
            $table->string('recv_phone');
            $table->string('recv_addr');
            $table->string('recv_addr_detail');
            $table->string('recv_msg');
            $table->timestamps();
        });

        Schema::create('xero_store_delivery_company', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('uri');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_store_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('method');
            $table->string('info');
            $table->integer('price');
            $table->integer('discount');
            $table->integer('millage');
            $table->integer('fare');
            $table->boolean('is_paid');
            $table->timestamps();
        });

        Schema::create('xero_store_order_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('xero_store_pay_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pay_id');
            $table->string('status');
            $table->timestamps();
        });
    }
}
