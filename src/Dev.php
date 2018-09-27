<?php

namespace Xpressengine\Plugins\XeroCommerce;

use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler;
use Xpressengine\Plugins\XeroCommerce\Models\DeliveryCompany;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
use Xpressengine\Plugins\XeroCommerce\Models\ShopUser;
use Xpressengine\Plugins\XeroCommerce\Plugin\Database;
use Xpressengine\Plugins\XeroCommerce\Plugin\Resources;
use Xpressengine\Plugins\XeroCommerce\Services\CartService;
use Xpressengine\Tag\Tag;
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
        $this->deleteTagInfo();
    }

    public function setConfig()
    {
        Resources::setConfig();
    }

    public function deleteTagInfo()
    {
        $tags = Tag::where('instance_id', 'xero_commerce')->get();

        foreach ($tags as $tag) {
            DB::select("delete from xe_taggables where tag_id='" . $tag->id . "'");
        }

        Tag::where('instance_id', 'xero_commerce')->delete();
    }

    public function makeShop($count = 1)
    {
        $engFaker = Factory::create(('en'));
        $users = User::get()->toArray();

        for ($i = 0; $i < $count; $i++) {
            $shop = new Shop();
            $shop->shop_name = $this->faker->name;
            $shop->shop_eng_name = $engFaker->firstName;
            if ($i == 0) {
                $shopType = Shop::TYPE_BASIC_SHOP;
            } else {
                $shopType = $this->faker->numberBetween(Shop::TYPE_STORE, Shop::TYPE_INDIVIDUAL);
            }
            $shop->shop_type = $shopType;
            $shop->state_approval = $this->faker->numberBetween(Shop::APPROVAL_WAITING, Shop::APPROVAL_REJECT);
            $shop->save();

            $shopUser = new ShopUser();
            $shopUser['shop_id'] = $shop->id;

            if ($shop->shop_type == Shop::TYPE_BASIC_SHOP) {
                $userId = User::where('display_name', 'admin')->orWhere('display_name', 'hero')->first()['id'];
            } else {
                $userId = $users[(rand(0, count($users) - 1))]['id'];
            }
            $shopUser['user_id'] = $userId;
            $shopUser->save();

            $shop->deliveryCompanys()->save(
                DeliveryCompany::first(),
                ['delivery_fare' => '3000', 'up_to_free' => '50000', 'is_default' => 1]
            );
        }
        return Shop::all();
    }

    public function makeProduct($count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            $product = new Product();
            $product->shop_id = rand(1, Shop::count());
            $product->product_code = $this->faker->numerify('###########');
            $product->name = $this->faker->word;
            $product->original_price = $this->faker->numberBetween(1, 50) * 1000;
            $product->sell_price = $product->original_price - ($product->original_price * rand(0, 10) / 100);
            $product->discount_percentage = round(
                (($product->original_price - $product->sell_price) * 100 / $product->original_price)
            );
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
        $this->makeShop(5);
        $this->makeProduct(10);
        $s = new CartHandler();
        $rand1 = rand(1, ProductOptionItem::count());
        $rand2 = rand(1, ProductOptionItem::count());
        $cg1 = $s->makeCartGroup(ProductOptionItem::find($rand1), $rand2);
        $cg2 = $s->makeCartGroup(ProductOptionItem::find($rand2), $rand1);
        $s->addCart(ProductOptionItem::find($rand1)->product, collect([$cg1]));
        $s->addCart(ProductOptionItem::find($rand2)->product, collect([$cg2]));
    }
}
