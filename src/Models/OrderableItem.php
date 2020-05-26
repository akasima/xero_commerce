<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

/**
 * 주문가능한 아이템 (CartItem, OrderItem 등)
 * Class OrderableItem
 * @package Xpressengine\Plugins\XeroCommerce\Models
 *
 * @property ProductVariant productVariant
 * @property Product product
 * @property Product product
 */
abstract class OrderableItem extends DynamicModel
{
    const SHIPPING_FEE_TYPE_PREPAY = 1;

    const SHIPPING_FEE_TYPE_POSTPAY = 2;

    const SHIPPING_FEE_TYPE_NAMES = [
        1 => '선불',
        2 => '착불',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id')->withTrashed();
    }

    function getCount()
    {
        $this->count;
    }

    abstract function getShippingFeeType();

    function getShippingFeeTypeName()
    {
        return array_search($this->shipping_fee_type, self::SHIPPING_FEE_TYPE_NAMES);
    }

    function getOriginalPrice()
    {
        return $this->productVariant->getOriginalPrice();
    }

    function getSellPrice()
    {
        return $this->productVariant->getSellPrice();
    }

    function getThumbnailSrc()
    {
        return $this->product->getThumbnailSrc();
    }

    public function getDiscountPrice()
    {
        return $this->getOriginalPrice() - $this->getSellPrice();
    }

    public function getFare()
    {
        if ($this->getShippingFeeTypeName() == '착불') {
            return 0;
        }
        if (!$this->product->isShipped()) {
            return 0;
        }

        return $this->product->getFare();
    }

}
