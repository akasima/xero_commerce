<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('xero_commerce_product_option')) {
            // 상품옵션 테이블 추가
            Schema::create('xero_commerce_product_option', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->index();
                $table->string('name');
                $table->text('values');
                $table->integer('sort_order');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce_product_custom_option')) {
            // 사용자의 입력을 받을 수 있는 커스텀옵션 테이블 추가
            Schema::create('xero_commerce_product_custom_option', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->index();
                $table->string('name');
                $table->string('type');
                $table->integer('sort_order');
                $table->boolean('is_required');
                $table->boolean('settings');
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('xero_commerce_product_option_item', 'combination_values')) {
            // OptionItem에 옵션값의 조합(combination_values) 칼럼 추가 (예시: {'색상':'블랙','사이즈':'S'})
            Schema::table('xero_commerce_product_option_item', function (Blueprint $table) {
                $table->text('combination_values')->after('name');
                // 아래 칼럼은 product 테이블의 option_type과 중복이므로 삭제
                $table->dropColumn('option_type');
            });
        }

        if (!Schema::hasColumn('xero_commerce_product_option_item_revision', 'combination_values')) {
            Schema::table('xero_commerce_product_option_item_revision', function (Blueprint $table) {
                $table->text('combination_values')->after('name');
                // 아래 칼럼은 product 테이블의 option_type과 중복이므로 삭제
                $table->dropColumn('option_type');
            });
        }

        if (!Schema::hasColumn('xero_commerce_products', 'option_type')) {
            // 상품 테이블에 option_type 추가 (단독형, 조합형)
            Schema::table('xero_commerce_products', function (Blueprint $table) {
                $table->string('option_type')
                    ->default(\Xpressengine\Plugins\XeroCommerce\Models\Product::OPTION_TYPE_COMBINATION_MERGE)
                    ->after('tax_type');
            });
        }

        if (!Schema::hasColumn('xero_commerce_products_revision', 'option_type')) {
            Schema::table('xero_commerce_products_revision', function (Blueprint $table) {
                $table->string('option_type')
                    ->default(\Xpressengine\Plugins\XeroCommerce\Models\Product::OPTION_TYPE_COMBINATION_MERGE)
                    ->after('tax_type');
            });
        }

        // 카트그룹과 주문그룹에 커스텀 옵션값 추가
        if (!Schema::hasColumn('xero_commerce_cart_group', 'custom_values')) {
            Schema::table('xero_commerce_cart_group', function (Blueprint $table) {
                $table->text('custom_values')->after('unit_type');
            });
        }

        if (!Schema::hasColumn('xero_commerce_order_item_group', 'custom_values')) {
            Schema::table('xero_commerce_order_item_group', function (Blueprint $table) {
                $table->text('custom_values')->after('unit_type');
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
        Schema::dropIfExists('xero_commerce_product_option');
        Schema::dropIfExists('xero_commerce_product_custom_option');

        if (Schema::hasColumn('xero_commerce_product_option_item', 'combination_values')) {
            Schema::table('xero_commerce_product_option_item', function (Blueprint $table) {
                $table->dropColumn('combination_values');
                $table->integer('option_type')->after('product_id');
            });
        }

        if (Schema::hasColumn('xero_commerce_product_option_item_revision', 'combination_values')) {
            Schema::table('xero_commerce_product_option_item_revision', function (Blueprint $table) {
                $table->dropColumn('combination_values');
                $table->integer('option_type')->after('product_id');
            });
        }

        if (Schema::hasColumn('xero_commerce_products', 'option_type')) {
            Schema::table('xero_commerce_products', function (Blueprint $table) {
                $table->dropColumn('option_type');
            });
        }

        if (Schema::hasColumn('xero_commerce_products_revision', 'option_type')) {
            Schema::table('xero_commerce_products_revision', function (Blueprint $table) {
                $table->dropColumn('option_type');
            });
        }

        if (Schema::hasColumn('xero_commerce_cart_group', 'custom_values')) {
            Schema::table('xero_commerce_cart_group', function (Blueprint $table) {
                $table->dropColumn('custom_values');
            });
        }

        if (Schema::hasColumn('xero_commerce_order_item_group', 'custom_values')) {
            Schema::table('xero_commerce_order_item_group', function (Blueprint $table) {
                $table->dropColumn('custom_values');
            });
        }
    }
}
