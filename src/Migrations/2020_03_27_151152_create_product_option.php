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
                $table->string('display_type');
                $table->json('values');
                $table->integer('sort_order');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce_product_suboption')) {
            // 사용자의 입력을 받을 수 있는 서브옵션 테이블 추가
            Schema::create('xero_commerce_product_suboption', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->index();
                $table->string('name');
                $table->string('input_type');
                $table->integer('sort_order');
                $table->boolean('is_required');
                $table->boolean('is_hidden');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('xero_commerce_product_option_item')) {
            // OptionItem에 옵션값의 조합(value_combination) 칼럼 추가 (예시: {'색상':'블랙','사이즈':'S'})
            Schema::table('xero_commerce_product_option_item', function (Blueprint $table) {
                $table->json('value_combination')->after('name');
                // 아래 칼럼은 product 테이블의 option_type과 중복이므로 삭제
                $table->dropColumn('option_type');
            });
        }

        if (!Schema::hasTable('xero_commerce_product_option_item_revision')) {
            Schema::table('xero_commerce_product_option_item_revision', function (Blueprint $table) {
                $table->json('value_combination')->after('name');
                // 아래 칼럼은 product 테이블의 option_type과 중복이므로 삭제
                $table->dropColumn('option_type');
            });
        }

        if (!Schema::hasTable('xero_commerce_products')) {
            // 상품 테이블에 option_type 추가 (단독형, 조합형)
            Schema::table('xero_commerce_products', function (Blueprint $table) {
                $table->string('option_type')
                    ->default(\Xpressengine\Plugins\XeroCommerce\Models\Product::OPTION_TYPE_SIMPLE)
                    ->after('tax_type');
            });
        }

        if (!Schema::hasTable('xero_commerce_products_revision')) {
            Schema::table('xero_commerce_products_revision', function (Blueprint $table) {
                $table->string('option_type')
                    ->default(\Xpressengine\Plugins\XeroCommerce\Models\Product::OPTION_TYPE_SIMPLE)
                    ->after('tax_type');
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
        Schema::dropIfExists('xero_commerce_product_suboption');

        Schema::table('xero_commerce_product_option_item', function (Blueprint $table) {
            $table->dropColumn('value_combination');
            $table->integer('option_type')->after('product_id');
        });

        Schema::table('xero_commerce_product_option_item_revision', function (Blueprint $table) {
            $table->dropColumn('value_combination');
            $table->integer('option_type')->after('product_id');
        });

        Schema::table('xero_commerce_products', function (Blueprint $table) {
            $table->dropColumn('option_type');
        });
        Schema::table('xero_commerce_products_revision', function (Blueprint $table) {
            $table->dropColumn('option_type');
        });
    }
}
