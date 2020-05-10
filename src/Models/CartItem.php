<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

/**
 * 카트 품목
 * Class CartItem
 * @package Xpressengine\Plugins\XeroCommerce\Models
 */
class CartItem extends OrderableItem
{
    protected $table = 'xero_commerce__cart_items';

    protected $fillable = [
        'custom_values',
        'count',
    ];

    protected $casts = [
        'custom_values' => 'json'
    ];

    function getCount()
    {
        return $this->count;
    }

    function setCount($count)
    {
        $this->count=$count;
    }

    public function sellSet()
    {
        return $this->belongsTo(v::class);
    }

    public function getCustomValues()
    {
        return $this->custom_values;
    }

    public function setCustomValues($customValues)
    {
        $this->custom_values = $customValues;
    }

    public function renderInformation()
    {
        // TODO: Implement renderInformation() method.
    }

    function getJsonFormat()
    {
        return [
            'id' => $this->id,
            'info' => $this->renderInformation(),
            'original_price' => $this->getOriginalPrice(),
            'sell_price' => $this->getSellPrice(),
            'discount_price' => $this->getDiscountPrice(),
            'fare' => $this->getFare(),
            'count' => $this->getCount(),
            'src' => $this->getThumbnailSrc(),
            'url'=> route('xero_commerce::product.show', ['strSlug' => $this->productVariant->slug]),
            'name' => $this->product->name,
            'variant_name' => $this->productVariant->name,
            'shop_carrier' => $this->product->shopCarrier,
            'shipping_fee' => $this->getShippingFeeType(),
            'min'=>$this->product->min_buy_count,
            'max'=>$this->product->max_buy_count
        ];
    }
}
