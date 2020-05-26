<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

class OrderItem extends OrderableItem
{
    protected $table = 'xero_commerce__order_items';

    protected $fillable = [
        'count',
        'shipping_fee_type',
        'original_price',
        'sell_price',
        'code',
    ];

    protected $casts = [
        'custom_options' => 'collection'
    ];

    const CODE_EXCHANGING = 1;
    const CODE_EXCHANGED = 2;
    const CODE_REFUNDING = 3;
    const CODE_REFUNDED = 4;
    const CODE_CANCELING = 5;
    const CODE_CANCELED = 6;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function children()
    {
        return $this->hasMany(OrderItem::class, 'parent_id');
    }

    public function customOptions()
    {
        return $this->hasMany(OrderItemCustomOption::class, 'order_item_id');
    }

    public function shipment()
    {
        return $this->hasOne(OrderShipment::class);
    }

    public function afterService()
    {
        return $this->hasOne(OrderAfterservice::class);
    }

    public function getShippingFeeType()
    {
        return $this->shipping_fee_type;
    }

    /**
     * @inheritDoc
     */
    public function getCustomOptions()
    {
        return $this->customOptions;
    }

    public function setCustomOptions(array $customOptions)
    {
        $this->customOptions()->createMany($customOptions);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user' => $this->order->userInfo,
            'order_no' => $this->order->order_no,
            'custom_options' => $this->customOptions,
            'name' => $this->product->name,
            'variant_name' => $this->productVariant->name,
            'original_price' => $this->getOriginalPrice(),
            'sell_price' => $this->getSellPrice(),
            'discount_price' => $this->getDiscountPrice(),
            'count' => $this->count,
            'src' => $this->getThumbnailSrc(),
            'status' => $this->shipment ? $this->shipment->getStatus() : '',
            'shipment' => $this->shipment ?: null,
            'shipment_url' => $this->shipment ? $this->shipment->geturl() : '',
            'fare' => $this->getFare(),
            'shipping_fee_type' => $this->getShippingFeeType(),
            'shipping_fee_type_name' => $this->getShippingFeeTypeName(),
            'shop_carrier' => $this->product->getShopCarrier(),
            'as' => $this->afterService
        ];
    }

}
