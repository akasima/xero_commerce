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
 * @property Product productWithTrashed
 */
abstract class OrderableItem extends DynamicModel
{
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productWithTrashed()
    {
        return $this->product()->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * @return array
     */
    abstract public function renderInformation();

    function getCount()
    {
        $this->count;
    }

    function getShippingFeeType()
    {
        return array_search($this->shipping_fee, Product::SHIPPING_FEE_TYPE);
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
        return $this->productWithTrashed->getThumbnailSrc();
    }

    public function getDiscountPrice()
    {
        return $this->getOriginalPrice() - $this->getSellPrice();
    }

    public function getFare()
    {
        if ($this->getShippingFeeType() == '착불') {
            return 0;
        }
        if (!$this->productWithTrashed->isShipped()) {
            return 0;
        }

        return $this->productWithTrashed->getFare();
    }

    public function renderSpanBr($var, $style = "")
    {
        return "<span style=\"{$style}\">{$var}</span> <br>";
    }

    abstract function getJsonFormat();
}
