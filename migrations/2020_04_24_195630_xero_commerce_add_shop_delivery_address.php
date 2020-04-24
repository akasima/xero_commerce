<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XeroCommerceAddShopDeliveryAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 배송옵션중 픽업을 위한 픽업장소 주소
        Schema::table('xero_commerce_shop_delivery', function (Blueprint $table) {
            $table->string('addr');
            $table->string('addr_detail');
            $table->string('addr_post');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xero_commerce_shop_delivery', function (Blueprint $table) {
            $table->dropColumn('addr');
            $table->dropColumn('addr_detail');
            $table->dropColumn('addr_post');
        });
    }
}
