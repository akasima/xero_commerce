<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use App\Facades\XeMedia;
use App\Facades\XeStorage;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Http\Request;
use Xpressengine\Media\Models\Image;
use Xpressengine\Plugins\XeroCommerce\Events\NewProductRegisterEvent;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCategory;
use Xpressengine\Plugins\XeroCommerce\Models\ProductLabel;
use Xpressengine\Plugins\XeroCommerce\Models\ProductRevision;
use Xpressengine\Plugins\XeroCommerce\Models\ShopUser;
use Xpressengine\User\Rating;

class ProductHandler
{
    const SORT_LOW_PRICE = 1;
    const SORT_HIGH_PRICE = 2;
    const SORT_PRODUCT_NAME_ASC = 3;
    const SORT_PRODUCT_NAME_DESC = 4;

    public static function getSortAble()
    {
        $sortAble[self::SORT_LOW_PRICE] = '낮은 가격순';
        $sortAble[self::SORT_HIGH_PRICE] = '높은 가격순';
        $sortAble[self::SORT_PRODUCT_NAME_ASC] = '상품명 오름차순';
        $sortAble[self::SORT_PRODUCT_NAME_DESC] = '상품명 내림차순';

        return $sortAble;
    }

    /**
     * @param  integer $productId productId
     *
     * @return Product
     */
    public function getProduct($productId)
    {
        $item = Product::where('id', $productId)->first();

        if(is_null($item)) {
            abort(500, '해당 상품이 존재하지 않습니다.');
        }

        return $item;
    }

    public function getProductsQueryForWidget($request)
    {
        $query = new Product();

        $query = $this->commonMakeWhere($request, $query);

        return $query;
    }

    /**
     * @param Request $request request
     *
     * @return Product
     */
    public function getProductsQueryForSetting(Request $request)
    {
        $query = new Product();

        $query = $this->settingMakeWhere($request, $query);
        $query = $this->commonMakeWhere($request, $query);
        $query = $this->filterByOwnShop($query);

        return $query;
    }

    public function getProductsQueryForModule(Request $request, $config)
    {
        $query = new Product();

        $query = $this->moduleMakeWhere($request, $query, $config);
        $query = $this->commonMakeWhere($request, $query);
        $query = $this->commonOrderBy($request, $query);

        return $query;
    }
	
    public function getProductsQueryForCommon(Request $request)
    {
        $query = new Product();

        $query = $this->commonMakeWhere($request, $query);
        $query = $this->commonOrderBy($request, $query);

        return $query;
    }

    private function filterByOwnShop($query)
    {
        if (Auth::check()) {
            if (Auth::user()->rating === Rating::SUPER) return $query;
            if (Auth::user()->rating === Rating::MANAGER) {
                $shopUserHandler = new ShopUserHandler();
                $shop_ids = $shopUserHandler->getUsersShop(Auth::id())->pluck('shop_id');
                $query = $query->whereIn('shop_id', $shop_ids);
            }
        } else {
            abort(500, '권한에러');
        }
        return $query;
    }

    private function moduleMakeWhere(Request $request, $query, $config)
    {
        $targetProductIds = [];
        if ($categoryItemId = $config->get('categoryItemId')) {
            $categoryItem = CategoryItem::where('id', $categoryItemId)->first();

            $categoryIds[] = $categoryItem->id;
            foreach ($categoryItem->descendants as $desc) {
                $categoryIds[] = $desc->id;
            }

            $productIds = ProductCategory::whereIn('category_id', $categoryIds)->pluck('product_id')->toArray();

            $targetProductIds = array_merge($targetProductIds, $productIds);
        }

        if ($labels = $config->get('labels')) {
            $productIds = ProductLabel::whereIn('label_id', $labels)->pluck('product_id')->toArray();

            $targetProductIds = array_intersect($targetProductIds, $productIds);
        }
		
        if ($tags = $config->get('tags')) {
			$productIds = Product::whereHas('tags', function($query) use ($tags) {
				$query->whereIn('word', $tags);
			})->pluck('id')->toArray();

            $targetProductIds = array_intersect($targetProductIds, $productIds);
        }

        $targetProductIds = array_unique($targetProductIds);
        $query = $query->where('state_display', Product::DISPLAY_VISIBLE);
        $query = $query->whereIn('id', $targetProductIds);

        return $query;
    }

    /**
     * @param Request $request request
     * @param Product $query product
     *
     * @return Product
     */
    private function settingMakeWhere(Request $request, $query)
    {
        return $query;
    }

    /**
     * @param Request $request request
     * @param Product $query product
     *
     * @return Product
     */
    private function commonMakeWhere(Request $request, $query)
    {
        $args = $request->all();

        if (isset($args['product_name']) == true) {
            $query = $query->where('name', 'like', '%' . $args['product_name'] . '%');
        }

        if (isset($args['product_code']) == true) {
            $query = $query->where('product_code', 'like', '%' . $args['product_code'] . '%');
        }

        if (isset($args['product_deal_state'])) {
            $query = $query->where('state_deal', $args['product_deal_state']);
        }

        if (isset($args['product_display_state'])) {
            $query = $query->where('state_display', $args['product_display_state']);
        }

        if (isset($args['product_tax_type']) == true) {
            $query = $query->where('tax_type', $args['product_tax_type']);
        }

        return $query;
    }

    private function commonOrderBy(Request $request, $query)
    {
        if ($sortType = $request->get('sort_type')) {
            switch ($sortType) {
                case self::SORT_LOW_PRICE:
                    $query = $query->orderBy('sell_price', 'asc');
                    break;
                case self::SORT_HIGH_PRICE:
                    $query = $query->orderBy('sell_price', 'desc');
                    break;
                case self::SORT_PRODUCT_NAME_ASC:
                    $query = $query->orderBy('name', 'asc');
                    break;
                case self::SORT_PRODUCT_NAME_DESC:
                    $query = $query->orderBy('name', 'desc');
                    break;
            }
        }

        return $query;
    }

    /**
     * @param  array $args productArgs
     *
     * @return integer
     */
    public function store(array $args)
    {
        $newProduct = new Product();
        $args = $this->numberFormatPrice($args);
        $newProduct->fill($args);
        $info = array_combine($args['infoKeys'], $args['infoValues']);
        $newProduct->detail_info = json_encode($info);

        $newProduct->save();

        $this->storeRevision($newProduct);

        foreach ($args['images'] as $image) {
            if ($image != null) {
                $this->saveImage($image, $newProduct);
            }
        }


        return $newProduct->id;
    }

    public function saveImage($imageParm, Product $newProduct, $key = null)
    {
        $file = XeStorage::upload($imageParm, 'public/xero_commerce/product');
        $imageFile = XeMedia::make($file);
        XeMedia::createThumbnails($imageFile, 'widen', config('xe.media.thumbnail.dimensions'));
        if (is_null($key)) {
            $newProduct->images()->attach($imageFile->id);
        } else {
            if ($existImage = $newProduct->images->get($key)) {
                $newProduct->images()->updateExistingPivot($existImage->id, ['image_id' => $imageFile->id]);
            }else{
                $newProduct->images()->attach($imageFile->id);
            }
        }

        return $imageFile;
    }

    public function removeImage(Product $product, $key)
    {
        $image = $product->images->get($key);
        $product->images()->detach($image->id);
    }

    public function update(Product $product, $args)
    {
        $args = $this->numberFormatPrice($args);
        $attributes = $product->getAttributes();

        foreach ($args as $name => $value) {
            if (array_key_exists($name, $attributes) === true) {
                $product->{$name} = $value;
            }
        }

        $info = array_combine(key_exists('infoKeys', $args) ? $args['infoKeys'] : [], key_exists('infoValues', $args) ? $args['infoValues'] : []);

        $product->detail_info = json_encode($info);

        foreach ($args['images'] as $key => $image) {
            if ($image != null) {
                if ($image == '__delete_file__') {
                    $this->removeImage($product, $key);
                } else {
                    $this->saveImage($image, $product, $key);
                }
            }
        }

        $product->save();

        $this->storeRevision($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        $this->storeRevision($product);
    }

    private function storeRevision($product)
    {
        $revisionNo = 0;
        $lastRevision = ProductRevision::where('id', $product->id)->max('revision_no');
        if ($lastRevision !== null) {
            $revisionNo = $lastRevision + 1;
        }

        $revisionProduct = new ProductRevision();

        $revisionProduct->fill($product->getAttributes());

        $revisionProduct->id = $product->id;
        $revisionProduct->revision_no = $revisionNo;
        $revisionProduct->detail_info = $product->detail_info;
        $revisionProduct->origin_deleted_at = $product->deleted_at;
        $revisionProduct->origin_created_at = $product->created_at;
        $revisionProduct->origin_updated_at = $product->updated_at;

        $revisionProduct->save();
    }

    public function setPublish($productId, $bool)
    {
        $product = Product::find($productId);
        $product->publish = $bool;
        return $product->save();
    }

    private function numberFormatPrice($args){
        if(array_has($args, 'sell_price')){
            $args['sell_price']=str_replace(',','',$args['sell_price']);
        }
        if(array_has($args,'original_price')){
            $args['original_price']=str_replace(',','',$args['original_price']);
        }
        return $args;
    }
}
