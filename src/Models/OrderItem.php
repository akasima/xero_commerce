<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

class OrderItem extends OrderableItem
{
    protected $table = 'xero_commerce__order_items';

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

    public function customOptions()
    {
        return $this->hasMany(OrderItemCustomOption::class, 'order_item_id');
    }

    /**
     * @return array
     */
    public function renderInformation()
    {
        $row = [];
        $row [] = '<a target="_blank' . now()->toTimeString() . '" href="' . route('xero_commerce::product.show', ['strSlug' => $this->productWithTrashed->slug]) . '">' . $this->renderSpanBr($this->productWithTrashed->name) . '</a>';
        $spanData = $this->productWithTrashed->name;
        $customOptions = $this->customOptions;
        if(!empty($customOptions)) {
            $spanData .= ' (';

            $spanData .= $customOptions->map(function($customOption) {
                return $customOption->name.' : '.$customOption->value;
            })->implode(', ');

            $spanData .= ')';
        }

        $spanData .= ' / ' . $this->getCount() . '개';

        $row [] = $this->renderSpanBr($spanData, "color: grey");
        $row [] = $this->renderSpanBr($this->productWithTrashed->shop->shop_name);

        return $row;
    }

    function getJsonFormat()
    {
        return [
            'id' => $this->id,
            'user' => $this->order->userInfo,
            'order_no' => $this->order->order_no,
            'info' => $this->renderInformation(),
            'custom_options' => $this->customOptions,
            'name' => $this->productWithTrashed->name,
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
            'shipping_fee' => $this->getShippingFeeType(),
            'shop_carrier' => $this->product->getShopCarrier(),
            'as' => $this->afterService
        ];
    }

    public function shipment()
    {
        return $this->hasOne(OrderShipment::class);
    }

    public function afterService()
    {
        return $this->hasOne(OrderAfterservice::class);
    }
}
