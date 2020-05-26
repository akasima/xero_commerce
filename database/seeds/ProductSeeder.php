<?php

namespace Xpressengine\Plugins\XeroCommerce\Database\Seeds;

use App\Facades\XeCategory;
use App\Facades\XeLang;
use App\Facades\XeMedia;
use App\Facades\XeStorage;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Badge;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCategory;
use Xpressengine\Plugins\XeroCommerce\Models\ProductLabel;
use Xpressengine\Plugins\XeroCommerce\Models\Products\BasicProduct;
use Xpressengine\Plugins\XeroCommerce\Models\ProductVariant;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSlugService;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = \XeCategory::createCate([
            'name' => '상품 분류'
        ]);

        for ($i = 1; $i <= 3; $i++) {
            $categoryItem = self::storeCagegoryItem($category, $i);
            self::storeProduct(4, $categoryItem->id);
        }

        ConfigSeeder::storeConfigData('categoryId', $category->id);
        self::storeDefaultMarks();

    }

    public static function storeCagegoryItem($category, $index)
    {
        $lang = ['ko' => '카테고리' . $index, 'en' => 'Category' . $index];
        $word = Plugin::XERO_COMMERCE_PREFIX . '::' . app('xe.keygen')->generate();
        $description = Plugin::XERO_COMMERCE_PREFIX . '::' . app('xe.keygen')->generate();
        foreach ($lang as $locale => $value) {
            XeLang::save($word, $locale, $value, false);
            XeLang::save($description, $locale, $value, false);
        }
        return XeCategory::createItem($category, ['word' => $word, 'description' => $description]);
    }

    public static function storeProduct($count, $category_id)
    {
        $faker = Factory::create('ko_kr');

        for ($i = 0; $i < $count; $i++) {
            $product = new BasicProduct();
            $product->shop_id = rand(1, Shop::count());
            $product->product_code = $faker->numerify('###########');
            $product->detail_info = [
                '상품정보' => '샘플 상품',
                '비고' => '수정해서 사용'
            ];
            $product->name = '지금부터 봄까지 입는 데일리 인기신상 ITEM' . ($i + 1);
            $product->sub_name = '간단한 상품설명';
            $product->original_price = $faker->numberBetween(1, 50) * 1000;
            $product->sell_price = $product->original_price - ($product->original_price * rand(0, 10) / 100);
            $product->discount_percentage = round(
                (($product->original_price - $product->sell_price) * 100 / $product->original_price)
            );
            $product->description = '상품설명페이지';
            $product->tax_type = rand(Product::TAX_TYPE_TAX, Product::TAX_TYPE_FREE);
            $product->state_display = Product::DISPLAY_VISIBLE;
            $product->state_deal = Product::DEAL_ON_SALE;
            $product->shop_carrier_id = Shop::find($product->shop_id)->carriers()->first()->pivot->id;
            $product->save();

            if (Product::count() == 4) {
                $url = file_get_contents(Plugin::path('assets/sample/tmp_tablist.jpg'));
            } elseif (Product::count() == 8) {
                $url = file_get_contents(Plugin::path('assets/sample/tmp_cross2.jpg'));
            } elseif (Product::count() == 12) {
                $url = file_get_contents(Plugin::path('assets/sample/tmp_cross.jpg'));
            } else {
                $url = file_get_contents(Plugin::path('assets/sample/tmp_product.jpg'));
            }

            $file = XeStorage::create($url, 'public/xero_commerce/product', 'default.jpg');
            $imageFile = XeMedia::make($file);
            XeMedia::createThumbnails($imageFile, 'widen', config('xe.media.thumbnail.dimensions'));
            $product->images()->attach($imageFile->id);

            self::storeProductOption($product->id);

            ProductSlugService::storeSlug($product, new Request());

            $newProductCategory = new ProductCategory();

            $newProductCategory->product_id = $product->id;
            $newProductCategory->category_id = $category_id;

            $newProductCategory->save();

            $labels = Label::pluck('id')->toArray();
            $labelCount = count($labels);

            for ($j = 0; $j < rand(0, $labelCount); $j++) {
                $newProductLabel = new ProductLabel();

                $newProductLabel->product_id = $product->id;
                $newProductLabel->label_id = $labels[rand(0, $labelCount - 1)];

                $newProductLabel->save();
            }
        }
    }

    public static function storeProductOption($product_id)
    {
        $faker = Factory::create('ko_kr');
        for ($i = 0; $i < rand(1, 4); $i++) {
            $op = new ProductVariant();
            $op->product_id = $product_id;

            if ($i == 0) {
                $op->additional_price = 0;
            } else {
                $op->additional_price = $faker->numberBetween(0, 10) * 500;
            }

            $op->name = '옵션' . ($i + 1);
            $op->stock = 10;
            $op->alert_stock = 1;
            $op->state_display = ProductVariant::DISPLAY_VISIBLE;
            $op->state_deal = ProductVariant::DEAL_ON_SALE;
            $op->save();
        }
    }

    /**
     * @return void
     */
    public static function storeDefaultMarks()
    {
        $labels[] = ['name' => '히트', 'eng_name' => 'hit'];
        $labels[] = ['name' => '추천', 'eng_name' => 'recommend'];
        $labels[] = ['name' => '신규', 'eng_name' => 'new'];
        $labels[] = ['name' => '인기', 'eng_name' => 'popular'];
        $labels[] = ['name' => '할인', 'eng_name' => 'sale'];

        foreach ($labels as $label) {
            $newLabel = new Label();
            $newLabel->name = $label['name'];
            $newLabel->eng_name = $label['eng_name'];

            $newLabel->save();
        }

        $badges[] = ['name' => '세일', 'eng_name' => 'sale'];
        $badges[] = ['name' => '히트', 'eng_name' => 'hit'];

        foreach ($badges as $badge) {
            $newBadge = new Badge();
            $newBadge->name = $badge['name'];
            $newBadge->eng_name = $badge['eng_name'];

            $newBadge->save();
        }
    }

}

