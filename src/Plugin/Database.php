<?php

namespace Xpressengine\Plugins\XeroCommerce\Plugin;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Database
{

    public static function addTable()
    {

        Schema::table('xero_commerce_order_log', function (Blueprint $table) {
            $table->string('ip');
            $table->string('url');
        });

        Schema::table('xero_commerce_pay_log', function (Blueprint $table) {
            $table->string('ip');
            $table->string('url');
        });
    }

    public static function create()
    {
        Schema::create('xero_commerce_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->string('name');
            $table->string('phone');
            $table->integer('level');
        });

        Schema::create('xero_commerce_user_delivery', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->integer('seq');
            $table->string('nickname');
            $table->string('name');
            $table->string('phone');
            $table->string('addr');
            $table->string('addr_detail');
            $table->string('addr_post');
            $table->string('msg')->nullable();
        });

        Schema::create('xero_commerce_user_agreement', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->integer('agreement_id');
            $table->timestamps();
        });

        Schema::create('xero_commerce_agreement', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('version');
            $table->string('name');
            $table->text('contents');
            $table->timestamps();
        });

        Schema::create('xero_commerce_shop', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shop_name');
            $table->string('shop_eng_name');
            $table->string('logo_path')->nullable();
            $table->string('background_path')->nullable();
            $table->integer('shop_type');
            $table->integer('state_approval');
            $table->text('delivery_info');
            $table->text('as_info');
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

        self::createProductTables();
        self::createProductOptionTables();

        Schema::create('xero_commerce_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image_id', 36);
            $table->morphs('imagable');
        });

        Schema::create('xero_commerce_product_slug', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('target_id');
            $table->string('slug');
            $table->string('product_name');
        });

        Schema::create('xero_commerce_product_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('category_id')->index();
        });

        Schema::create('xero_commerce_label', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('eng_name');
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
        });

        Schema::create('xero_commerce_badge', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('eng_name');
        });

        Schema::create('xero_commerce_product_label', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('label_id')->index();
        });

        Schema::create('xero_commerce_order', function (Blueprint $table) {
            $table->string('id');
            $table->string('order_no');
            $table->string('user_id', 36);
            $table->smallInteger('code');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_commerce_order_agreement', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->integer('agreement_id');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_commerce_qna', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('type');
            $table->string('title');
            $table->text('content');
            $table->integer('user_id');
            $table->boolean('privacy')->default(false);
            $table->timestamps();
        });

        Schema::create('xero_commerce_cart', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->smallInteger('delivery_pay');
            $table->morphs('type');
            $table->string('order_id')->nullable();
            $table->timestamps();
        });

        Schema::create('xero_commerce_wish', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 36);
            $table->morphs('type');
            $table->timestamps();
        });

        Schema::create('xero_commerce_cart_group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cart_id');
            $table->morphs('unit');
            $table->integer('count');
        });

        Schema::create('xero_commerce_order_item', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->smallInteger('delivery_pay');
            $table->integer('delivery_id');
            $table->morphs('type');
            $table->integer('original_price');
            $table->integer('sell_price');
            $table->smallInteger('code');
            $table->timestamps();
        });


        Schema::create('xero_commerce_order_item_group', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_item_id');
            $table->morphs('unit');
            $table->integer('count');
        });

        Schema::create('xero_commerce_order_delivery', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_item_id');
            $table->smallInteger('status');
            $table->string('ship_no');
            $table->integer('company_id');
            $table->string('recv_name');
            $table->string('recv_phone');
            $table->string('recv_addr');
            $table->string('recv_addr_detail');
            $table->string('recv_addr_post');
            $table->string('recv_msg')->nullable();
            $table->timestamps();
        });

        Schema::create('xero_commerce_order_afterservice', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('order_item_id');
            $table->text('reason');
            $table->integer('delivery_company_id');
            $table->string('ship_no');
            $table->boolean('received');
            $table->boolean('complete');
        });

        Schema::create('xero_commerce_delivery_company', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('type')->default(0);
            $table->string('name');
            $table->string('uri');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_commerce_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('method');
            $table->text('info');
            $table->integer('price');
            $table->integer('discount');
            $table->integer('millage');
            $table->integer('fare');
            $table->boolean('is_paid');
            $table->text('receipt');
            $table->timestamps();
        });

        Schema::create('xero_commerce_order_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('status');
            $table->string('ip');
            $table->string('url');
            $table->timestamps();
        });

        Schema::create('xero_commerce_pay_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pay_id');
            $table->string('status');
            $table->string('ip');
            $table->string('url');
            $table->timestamps();
        });

        \Xpressengine\XePlugin\XeroPay\Resources::makeDataTable();
    }

    /**
     * @return void
     */
    private static function createProductTables()
    {
        //상품 테이블 생성
        Schema::create('xero_commerce_products', function (Blueprint $table) {
            $table->increments('id');
            $table = self::setProductTableColumns($table);
            $table->softDeletes();
            $table->timestamps();
        });

        //상품 Revision 테이블 생성
        Schema::create('xero_commerce_products_revision', function (Blueprint $table) {
            $table->increments('revision_id');
            $table->integer('revision_no')->default(0);
            $table->integer('id');

            $table = self::setProductTableColumns($table);

            //수정 전 product의 timestamp
            $table->timestamp('origin_deleted_at')->nullable();
            $table->timestamp('origin_created_at');
            $table->timestamp('origin_updated_at');
            //수정 내역의 timestamp
            $table->timestamp('revision_deleted_at')->nullable();
            $table->timestamp('revision_created_at');
            $table->timestamp('revision_updated_at');

            $table->index('id');
        });
    }

    public static function changeTable()
    {
        Schema::table('xero_commerce_products', function (Blueprint $table) {
            $table->boolean('publish')->default(false);
        });
    }

    /**
     * @param Blueprint $table 상품 테이블 컬럼 정의
     *
     * @return Blueprint
     */
    private static function setProductTableColumns($table)
    {
        $table->integer('shop_id');
        $table->boolean('publish')->default(false);
        $table->string('product_code', 32)->nullable();
        $table->string('name')->nullable();
        $table->string('sub_name')->nullable();
        $table->integer('original_price')->nullable();
        $table->integer('sell_price')->nullable();
        $table->double('discount_percentage')->nullable();
        $table->integer('min_buy_count')->nullable();
        $table->integer('max_buy_count')->nullable();
        $table->text('description')->nullable();
        $table->text('detail_info')->nullable();
        $table->integer('badge_id')->nullable();
        $table->integer('tax_type')->nullable();
        $table->integer('state_display')->nullable();
        $table->integer('state_deal')->nullable();
        $table->integer('shop_delivery_id')->nullable();

        return $table;
    }

    private static function createProductOptionTables()
    {
        Schema::create('xero_commerce_product_option_item', function (Blueprint $table) {
            $table->increments('id');
            $table = self::setProductOptionTableColumns($table);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('xero_commerce_product_option_item_revision', function (Blueprint $table) {
            $table->increments('revision_id');
            $table->integer('revision_no')->default(0);
            $table->integer('id');

            $table = self::setProductOptionTableColumns($table);

            //수정 전 option의 timestamp
            $table->timestamp('origin_deleted_at')->nullable();
            $table->timestamp('origin_created_at');
            $table->timestamp('origin_updated_at');
            //수정 내역의 timestamp
            $table->timestamp('revision_deleted_at')->nullable();
            $table->timestamp('revision_created_at');
            $table->timestamp('revision_updated_at');

            $table->index('id');
        });
    }

    private static function setProductOptionTableColumns($table)
    {
        $table->integer('product_id')->index();
        $table->integer('option_type');
        $table->string('name');
        $table->integer('addition_price');
        $table->integer('stock');
        $table->integer('alert_stock')->nullable();
        $table->integer('state_display');
        $table->integer('state_deal');

        return $table;
    }
}
