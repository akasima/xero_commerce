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
        'custom_options',
        'count',
    ];

    protected $casts = [
        'custom_options' => 'collection'
    ];

    function getCount()
    {
        return $this->count;
    }

    function setCount($count)
    {
        $this->count=$count;
    }

    public function getCustomOptions()
    {
        return $this->custom_options;
    }

    public function setCustomOptions(array $customOptions)
    {
        $this->custom_options = $customOptions;
    }

    public function getShippingFeeType()
    {
        return $this->shipping_fee_type;
    }

    function toArray()
    {
        return [
            'id' => $this->id,
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
            'shipping_fee_type' => $this->getShippingFeeType(),
            'shipping_fee_type_name' => $this->getShippingFeeTypeName(),
            'min'=>$this->product->min_buy_count,
            'max'=>$this->product->max_buy_count,
            'custom_options' => $this->getCustomOptions(),
        ];
    }
}
