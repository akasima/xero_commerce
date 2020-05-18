<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

class OrderItem extends OrderableItem
{
    protected $table = 'xero_commerce__order_items';

    protected $casts = [
        'custom_values' => 'collection'
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

    /**
     * @return array
     */
    public function renderInformation()
    {
        $row = [];
        $row [] = '<a target="_blank' . now()->toTimeString() . '" href="' . route('xero_commerce::product.show', ['strSlug' => $this->productWithTrashed->slug]) . '">' . $this->renderSpanBr($this->productWithTrashed->name) . '</a>';
        $spanData = $this->productWithTrashed->name;
        $customValues = $this->custom_values;
        if(!empty($customValues)) {
            $spanData .= ' (';

            foreach ($customValues as $key => $value) {
                $spanData .= $key.' : '.$value;
                // 마지막 루프면
                if($key == array_keys($customValues)[count($customValues)-1]) {
                    $spanData .= ')';
                } else {
                    $spanData .= ', ';
                }
            }
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
            'custom_values' => $this->custom_values,
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
