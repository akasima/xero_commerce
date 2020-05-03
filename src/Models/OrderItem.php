<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

class OrderItem extends SellSet
{
    protected $table = 'xero_commerce__order_items';

    const EXCHANGING = 1;
    const EXCHANGED = 2;
    const REFUNDING = 3;
    const REFUNDED = 4;
    const CANCELING = 5;
    const CANCELED = 6;

    public function sellGroups()
    {
        return $this->hasMany(OrderItemGroup::class);
    }

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
        $row [] = '<a target="_blank' . now()->toTimeString() . '" href="' . route('xero_commerce::product.show', ['strSlug' => $this->forcedSellType()->getSlug()]) . '">' . $this->renderSpanBr($this->forcedSellType()->getName()) . '</a>';
        $this->sellGroups->each(function (SellGroup $group) use (&$row) {
            $spanData = $group->forcedSellUnit()->getName();
            $customValues = $group->getCustomValues();
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

            $spanData .= ' / ' . $group->getCount() . '개';

            $row [] = $this->renderSpanBr($spanData, "color: grey");
        });
        $row [] = $this->renderSpanBr($this->forcedSellType()->getShop()->shop_name);

        return $row;
    }

    function getJsonFormat()
    {
        return [
            'id' => $this->id,
            'user' => $this->order->userInfo,
            'order_no' => $this->order->order_no,
            'info' => $this->renderInformation(),
            'options' => $this->sellGroups->map(function (SellGroup $sellGroup) {
                return $sellGroup->getJsonFormat();
            }),
            'name' => $this->forcedSellType()->getName(),
            'original_price' => $this->getOriginalPrice(),
            'sell_price' => $this->getSellPrice(),
            'discount_price' => $this->getDiscountPrice(),
            'count' => $this->getCount(),
            'src' => $this->getThumbnailSrc(),
            'status' => $this->shipment ? $this->shipment->getStatus() : '',
            'shipment' => $this->shipment ?: null,
            'shipment_url' => $this->shipment ? $this->shipment->geturl() : '',
            'fare' => $this->getFare(),
            'shipping_fee' => $this->getShippingFee(),
            'shop_carrier' => $this->sellType->getShopCarrier(),
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
