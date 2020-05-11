<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XeroCommerceCreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('xero_commerce__userinfos')) {
            Schema::create('xero_commerce__userinfos', function (Blueprint $table) {
                $table->increments('id');
                $table->string('user_id', 36);
                $table->string('name');
                $table->string('phone');
                $table->integer('level');
            });
        }

        if (!Schema::hasTable('xero_commerce__user_addresses')) {
            Schema::create('xero_commerce__user_addresses', function (Blueprint $table) {
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
        }

        if (!Schema::hasTable('xero_commerce__user_agreement')) {
            Schema::create('xero_commerce__user_agreement', function (Blueprint $table) {
                $table->increments('id');
                $table->string('user_id', 36);
                $table->integer('agreement_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__agreements')) {
            Schema::create('xero_commerce__agreements', function (Blueprint $table) {
                $table->increments('id');
                $table->string('type');
                $table->string('version');
                $table->string('name');
                $table->text('contents');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__shops')) {
            Schema::create('xero_commerce__shops', function (Blueprint $table) {
                $table->increments('id');
                $table->string('shop_name');
                $table->string('shop_eng_name')->nullable();
                $table->string('logo_path')->nullable();
                $table->string('background_path')->nullable();
                $table->integer('shop_type');
                $table->integer('state_approval')->default(0);
                $table->text('shipping_info')->nullable();
                $table->text('as_info')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__shop_user')) {
            Schema::create('xero_commerce__shop_user', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('shop_id');
                $table->string('user_id', 36);
            });
        }

        if (!Schema::hasTable('xero_commerce__shop_carrier')) {
            Schema::create('xero_commerce__shop_carrier', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('shop_id');
                $table->integer('carrier_id');
                $table->integer('fare');
                $table->integer('up_to_free');
                $table->boolean('is_default');
                $table->string('addr');
                $table->string('addr_detail');
                $table->string('addr_post');
            });
        }

        self::createProductTables();
        self::createProductOptionTables();

        if (!Schema::hasTable('xero_commerce__images')) {
            Schema::create('xero_commerce__images', function (Blueprint $table) {
                $table->increments('id');
                $table->string('image_id', 36);
                $table->morphs('imagable');
            });
        }

        if (!Schema::hasTable('xero_commerce__product_category')) {
            Schema::create('xero_commerce__product_category', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id');
                $table->integer('category_id')->index();
            });
        }

        if (!Schema::hasTable('xero_commerce__labels')) {
            Schema::create('xero_commerce__labels', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('eng_name');
                $table->string('background_color')->nullable();
                $table->string('text_color')->nullable();
            });
        }

        if (!Schema::hasTable('xero_commerce__badges')) {
            Schema::create('xero_commerce__badges', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('eng_name');
                $table->string('background_color')->nullable();
                $table->string('text_color')->nullable();
            });
        }

        if (!Schema::hasTable('xero_commerce__product_label')) {
            Schema::create('xero_commerce__product_label', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id');
                $table->integer('label_id')->index();
            });
        }

        if (!Schema::hasTable('xero_commerce__orders')) {
            Schema::create('xero_commerce__orders', function (Blueprint $table) {
                $table->string('id');
                $table->string('order_no');
                $table->string('user_id', 36);
                $table->smallInteger('code');
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__order_agreement')) {
            Schema::create('xero_commerce__order_agreement', function (Blueprint $table) {
                $table->increments('id');
                $table->string('order_id');
                $table->integer('agreement_id');
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__qnas')) {
            Schema::create('xero_commerce__qnas', function (Blueprint $table) {
                $table->increments('id');
                $table->morphs('type');
                $table->string('title');
                $table->text('content');
                $table->string('user_id', 36);
                $table->boolean('privacy')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__feedbacks')) {
            Schema::create('xero_commerce__feedbacks', function (Blueprint $table) {
                $table->increments('id');
                $table->morphs('type');
                $table->string('title');
                $table->integer('score');
                $table->text('content');
                $table->string('user_id', 36);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__cart_items')) {
            Schema::create('xero_commerce__cart_items', function (Blueprint $table) {
                $table->increments('id');
                $table->string('user_id', 36);
                $table->integer('product_id');
                $table->integer('product_variant_id');
                $table->text('custom_values');
                $table->integer('count');
                $table->smallInteger('shipping_fee');
                // 주문후 장바구니에서 주문된 상품을 지우기 위한 order_id
                $table->integer('order_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__wishes')) {
            Schema::create('xero_commerce__wishes', function (Blueprint $table) {
                $table->increments('id');
                $table->string('user_id', 36);
                $table->integer('product_id');
                $table->timestamps();
            });
        }


        if (!Schema::hasTable('xero_commerce__order_items')) {
            Schema::create('xero_commerce__order_items', function (Blueprint $table) {
                $table->increments('id');
                $table->string('order_id');
                $table->integer('product_id');
                $table->integer('product_variant_id');
                $table->text('custom_values');
                $table->integer('count');
                $table->smallInteger('shipping_fee');
                $table->integer('shipment_id')->nullable();
                $table->integer('original_price');
                $table->integer('sell_price');
                $table->smallInteger('code');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__order_shipments')) {
            Schema::create('xero_commerce__order_shipments', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('order_item_id');
                $table->smallInteger('status');
                $table->string('ship_no');
                $table->integer('carrier_id');
                $table->string('recv_name');
                $table->string('recv_phone');
                $table->string('recv_addr');
                $table->string('recv_addr_detail');
                $table->string('recv_addr_post');
                $table->string('recv_msg')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__order_afterservices')) {
            Schema::create('xero_commerce__order_afterservices', function (Blueprint $table) {
                $table->increments('id');
                $table->string('type');
                $table->integer('order_item_id');
                $table->text('reason');
                $table->integer('carrier_id');
                $table->string('ship_no');
                $table->boolean('received');
                $table->boolean('complete');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__carriers')) {
            Schema::create('xero_commerce__carriers', function (Blueprint $table) {
                $table->increments('id');
                $table->smallInteger('type')->default(0);
                $table->string('name');
                $table->string('uri');
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__payments')) {
            Schema::create('xero_commerce__payments', function (Blueprint $table) {
                $table->increments('id');
                $table->string('order_id');
                $table->string('method');
                $table->text('info');
                $table->integer('price');
                $table->integer('discount');
                $table->integer('millage');
                $table->integer('fare');
                $table->boolean('is_paid')->default(false);
                $table->text('receipt')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__order_logs')) {
            Schema::create('xero_commerce__order_logs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('order_id');
                $table->string('status');
                $table->string('ip');
                $table->string('url');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__pay_logs')) {
            Schema::create('xero_commerce__pay_logs', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('pay_id');
                $table->string('status');
                $table->string('ip');
                $table->string('url');
                $table->timestamps();
            });
        }

        \Xpressengine\XePlugin\XeroPay\Resources::makeDataTable();
    }

    /**
     * @return void
     */
    private static function createProductTables()
    {
        //상품 테이블 생성
        if (!Schema::hasTable('xero_commerce__products')) {
            Schema::create('xero_commerce__products', function (Blueprint $table) {
                $table->increments('id');
                $table = self::setProductTableColumns($table);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        //상품 Revision 테이블 생성
        if (!Schema::hasTable('xero_commerce__product_revisions')) {
            Schema::create('xero_commerce__product_revisions', function (Blueprint $table) {
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
    }

    /**
     * @param Blueprint $table 상품 테이블 컬럼 정의
     *
     * @return Blueprint
     */
    private static function setProductTableColumns($table)
    {
        $table->integer('shop_id');
        $table->string('type')->default(\Xpressengine\Plugins\XeroCommerce\Models\Product::$singleTableType);
        $table->boolean('publish')->default(false);
        $table->string('product_code', 32)->nullable();
        $table->string('name')->nullable();
        $table->string('sub_name')->nullable();
        $table->string('slug')->nullable();
        $table->integer('original_price')->nullable();
        $table->integer('sell_price')->nullable();
        $table->double('discount_percentage')->nullable();
        $table->integer('min_buy_count')->nullable();
        $table->integer('max_buy_count')->nullable();
        $table->text('description')->nullable();
        $table->text('detail_info')->nullable();
        $table->integer('badge_id')->nullable();
        $table->integer('tax_type')->nullable();
        $table->string('option_type')->default(\Xpressengine\Plugins\XeroCommerce\Models\Product::OPTION_TYPE_COMBINATION_MERGE);
        $table->integer('state_display')->nullable();
        $table->integer('state_deal')->nullable();
        $table->integer('shop_carrier_id')->nullable();

        return $table;
    }

    private static function createProductOptionTables()
    {
        // 상품옵션 테이블 추가
        if (!Schema::hasTable('xero_commerce__product_options')) {
            Schema::create('xero_commerce__product_options', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->index();
                $table->string('name');
                $table->text('values');
                $table->integer('sort_order');
                $table->timestamps();
            });
        }

        // 사용자의 입력을 받을 수 있는 커스텀옵션 테이블 추가
        if (!Schema::hasTable('xero_commerce__product_custom_options')) {
            Schema::create('xero_commerce__product_custom_options', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->index();
                $table->string('name');
                $table->string('description');
                $table->string('type');
                $table->integer('sort_order');
                $table->boolean('is_required');
                $table->boolean('settings');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__product_variants')) {
            Schema::create('xero_commerce__product_variants', function (Blueprint $table) {
                $table->increments('id');
                $table = self::setProductVariationTableColumns($table);
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce__product_variant_revisions')) {
            Schema::create('xero_commerce__product_variant_revisions', function (Blueprint $table) {
                $table->increments('revision_id');
                $table->integer('revision_no')->default(0);
                $table->integer('id');

                $table = self::setProductVariationTableColumns($table);

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
    }

    private static function setProductVariationTableColumns($table)
    {
        $table->integer('product_id')->index();
        $table->string('name');
        // OptionItem에 옵션값의 조합(combination_values) 칼럼 추가 (예시: {'색상':'블랙','사이즈':'S'})
        $table->text('combination_values');
        $table->integer('additional_price');
        $table->integer('stock');
        $table->integer('alert_stock')->nullable();
        $table->integer('state_display');
        $table->integer('state_deal');

        return $table;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('xero_commerce__userinfos');

        Schema::dropIfExists('xero_commerce__user_addresses');

        Schema::dropIfExists('xero_commerce__user_agreement');

        Schema::dropIfExists('xero_commerce__agreements');

        Schema::dropIfExists('xero_commerce__shops');

        Schema::dropIfExists('xero_commerce__shop_user');

        Schema::dropIfExists('xero_commerce__shop_carrier');

        self::dropIfExistsProductTables();
        self::dropIfExistsProductOptionTables();

        Schema::dropIfExists('xero_commerce__images');

        Schema::dropIfExists('xero_commerce__product_category');

        Schema::dropIfExists('xero_commerce__labels');

        Schema::dropIfExists('xero_commerce__badges');

        Schema::dropIfExists('xero_commerce__product_label');

        Schema::dropIfExists('xero_commerce__orders');

        Schema::dropIfExists('xero_commerce__order_agreement');

        Schema::dropIfExists('xero_commerce__qnas');

        Schema::dropIfExists('xero_commerce__feedbacks');

        Schema::dropIfExists('xero_commerce__cart_items');

        Schema::dropIfExists('xero_commerce__wishes');

        Schema::dropIfExists('xero_commerce__order_items');

        Schema::dropIfExists('xero_commerce__order_shipments');

        Schema::dropIfExists('xero_commerce__order_afterservices');

        Schema::dropIfExists('xero_commerce__carriers');

        Schema::dropIfExists('xero_commerce__payments');

        Schema::dropIfExists('xero_commerce__order_logs');

        Schema::dropIfExists('xero_commerce__pay_logs');

        \Xpressengine\XePlugin\XeroPay\Resources::deleteDataTable();
    }

    /**
     * @return void
     */
    private static function dropIfExistsProductTables()
    {
        //상품 테이블 생성
        Schema::dropIfExists('xero_commerce__products');

        //상품 Revision 테이블 생성
        Schema::dropIfExists('xero_commerce__product_revisions');
    }

    private static function dropIfExistsProductOptionTables()
    {
        Schema::dropIfExists('xero_commerce__product_options');

        Schema::dropIfExists('xero_commerce__product_custom_options');

        Schema::dropIfExists('xero_commerce__product_variants');

        Schema::dropIfExists('xero_commerce__product_variant_revisions');
    }

}
