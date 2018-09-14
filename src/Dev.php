<?php

namespace Xpressengine\Plugins\XeroStore;

use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Xpressengine\Plugins\XeroStore\Models\Product;
use Xpressengine\Plugins\XeroStore\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroStore\Plugin\Database;
use Xpressengine\Plugins\XeroStore\Plugin\Resources;

class Dev
{
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
    }

    public function resetTable()
    {
        $this->dropTable();
        $this->makeTable();
    }

    public function setConfig()
    {
        Resources::setConfig();
    }

    public function makeProduct($count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $faker = Factory::create('ko_kr');
            $product = new Product();
            $product->store_id = 1;
            $product->product_code = $faker->numerify('###########');
            $product->name = $faker->word;
            $product->original_price = $faker->numberBetween(1, 50) * 1000;
            $product->sell_price = $faker->numberBetween(0, 5) * 500;
            $product->discount_percentage = round(($product->sell_price*100 / $product->original_price));
            $product->description = $faker->text(100);
            $product->state_display = 1;
            $product->state_deal = 1;
            $product->save();
            $this->makeProductOption($product->id);
        }
        return Product::all();
    }

    public function makeProductOption($product_id)
    {
        $op = new ProductOptionItem();
        $faker = Factory::create('ko_kr');
        $op->product_id = $product_id;
        $op->option_type = 1;
        $op->name = $faker->colorName;
        $op->addition_price = $faker->numberBetween(0, 10) * 500;
        $op->stock = 10;
        $op->alert_stock = 1;
        $op->state_display = 1;
        $op->state_deal = 1;
        $op->save();
    }
}
