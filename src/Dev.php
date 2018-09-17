<?php

namespace Xpressengine\Plugins\XeroCommerce;

use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Xpressengine\Plugins\XeroCommerce\Models\DeliveryCompany;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Models\Store;
use Xpressengine\Plugins\XeroCommerce\Plugin\Database;
use Xpressengine\Plugins\XeroCommerce\Plugin\Resources;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;
use Xpressengine\User\Models\User;

class Dev
{
    public $faker;

    public function __construct()
    {
        $this->faker = Factory::create('ko_kr');
    }

    public function makeTable()
    {
        Database::create();
    }

    public function dropTable()
    {
        $tables = DB::select('SHOW TABLES LIKE "xe_xero_store_%"');
        foreach ($tables as $table) {
            $table_name = str_replace('xe_', '', head($table));
            Schema::dropIfExists($table_name);
            dump($table_name);
        }

        $tables = DB::select('SHOW TABLES LIKE "xe_xero_commerce_%"');
        foreach ($tables as $table) {
            $table_name = str_replace('xe_', '', head($table));
            Schema::dropIfExists($table_name);
            dump($table_name);
        }
    }

    public function resetTable()
    {
        $this->dropTable();
        $this->makeTable();
        $this->setting();
    }

    public function setConfig()
    {
        Resources::setConfig();
    }

    public function makeStore($count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $store = new Store();
            $store->store_name = $this->faker->domainName;
            $store->user_id = User::first()->id;
            $store->store_type = 1;
            $store->save();
            $store->deliveryCompanys()->save(
                DeliveryCompany::first(),
                ['delivery_fare' => '3000', 'up_to_free' => '50000', 'is_default' => 1]
            );
        }
        return Store::all();
    }

    public function makeProduct($count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $product = new Product();
            $product->store_id = rand(1, Store::count());
            $product->product_code = $this->faker->numerify('###########');
            $product->name = $this->faker->word;
            $product->original_price = $this->faker->numberBetween(1, 50) * 1000;
            $product->sell_price = $this->faker->numberBetween(0, 5) * 500;
            $product->discount_percentage = round(($product->sell_price * 100 / $product->original_price));
            $product->description = $this->faker->text(100);
            $product->state_display = Product::DISPLAY_VISIBLE;
            $product->state_deal = Product::DEAL_ON_SALE;
            $product->save();
            $this->makeProductOption($product->id);
        }
        return Product::all();
    }

    public function makeProductOption($product_id)
    {
        $op = new ProductOptionItem();
        $op->product_id = $product_id;
        $op->option_type = ProductOptionItem::TYPE_DEFAULT_OPTION;
        $op->name = $this->faker->colorName;
        $op->addition_price = $this->faker->numberBetween(0, 10) * 500;
        $op->stock = 10;
        $op->alert_stock = 1;
        $op->state_display = ProductOptionItem::DISPLAY_VISIBLE;
        $op->state_deal = ProductOptionItem::DEAL_ON_SALE;
        $op->save();
    }

    public function makeDeliveryCompany()
    {
        $dc = new DeliveryCompany();
        $dc->name = '한진택배';
        $dc->uri = '#';
        $dc->save();
        return $dc;
    }

    public function setting()
    {
        $this->makeDeliveryCompany();
        $this->makeStore(5);
        $this->makeProduct(10);
        $s = new CartService();
        $rand1 = rand(1, ProductOptionItem::count());
        $rand2 = rand(1, ProductOptionItem::count());
        $s->addList(ProductOptionItem::find($rand1), $rand2);
        $s->addList(ProductOptionItem::find($rand2), $rand1);
    }
}
