<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\Products;

use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderableItem;
use Xpressengine\Plugins\XeroCommerce\Models\Product;

/**
 * 번들 상품
 * 다양한 품목들을 묶어 수량,옵션을 정해두는 상품
 *
 * @author darron1217
 *
 */
class BundleProduct extends Product
{

    public static $singleTableType = 'bundle';

    public static $singleTableName = '번들 상품';

    public function __construct(array $attributes = [])
    {
        $this->fillable[] = 'bundle_items';

        parent::__construct($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(BundleProductItem::class, 'parent_product_id');
    }

    public function setBundleItemsAttribute($items)
    {
        if($items) {
            static::saved(function($model) use ($items) {
                $model->items()->delete();
                $model->items()->createMany($items);
            });
        }

    }

    public static function getSettingsCreateFields()
    {
        return view('xero_commerce::views.settings.product.bundle.fields')->render();
    }

    public static function getSettingsEditFields(Product $product)
    {
        return view('xero_commerce::views.settings.product.bundle.fields', ['product'=>$product])->render();
    }

    public static function getSettingsShowView(Product $product)
    {
        return view('xero_commerce::views.settings.product.bundle.show', ['product'=>$product])->render();
    }

    /**
     * 번들상품 주문시 번들품목들을 넣어주는 함수
     * @param Order $order
     * @param OrderableItem $item
     */
    public function newOrderItem(Order $order, OrderableItem $item)
    {
        $orderItem = parent::newOrderItem($order, $item);
        $orderItem->save();

        foreach ($this->items as $bundleItem) {
            $childOrderItem = parent::newOrderItem($order, $bundleItem);
            $childOrderItem->parent_id = $orderItem->id;
            $childOrderItem->save();
        }

        return $orderItem;
    }

}
