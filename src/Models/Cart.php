<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

class Cart extends SellSet
{
    protected $table = 'xero_commerce_cart';

    public function sellGroups()
    {
        return $this->hasMany(CartGroup::class);
    }

    /**
     * @return array
     */
    public function renderInformation()
    {
        $row = [];
        $row [] = '<a target="_blank' . now()->toTimeString() . '" href="' . route('xero_commerce::product.show', ['strSlug' => $this->forcedSellType()->getSlug()]) . '">' . $this->renderSpanBr($this->forcedSellType()->getName()) . '</a>';
        $row [] = $this->renderSpanBr($this->forcedSellType()->getInfo());
        $this->sellGroups->each(function (SellGroup $group) use (&$row) {
            $row [] = $this->renderSpanBr($group->forcedSellUnit()->getName() . ' / ' . $group->getCount() . '개', "color: grey");
        });

        $row [] = $this->renderSpanBr($this->forcedSellType()->getShop()->shop_name);

        return $row;
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
            'url'=> route('xero_commerce::product.show', ['strSlug' => $this->forcedSellType()->getSlug()]),
            'option_list' => $this->forcedSellType()->sellUnits->map(function (sellUnit $sellUnit) {
                return $sellUnit->getJsonFormat();
            }),
            'choose' => $this->sellGroups->map(function (SellGroup $sellGroup) {
                return $sellGroup->getJsonFormat();
            }),
            'name' => $this->forcedSellType()->getName(),
            'delivery' => $this->forcedSellType()->getDelivery(),
            'pay' => $this->getDeliveryPay(),
            'min'=>$this->forcedSellType()->min_buy_count,
            'max'=>$this->forcedSellType()->max_buy_count
        ];
    }
}
