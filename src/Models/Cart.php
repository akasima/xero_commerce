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
        $row=[];
        $row []= $this->renderSpanBr($this->sellType->getName());
        $this->sellGroups->each(function(SellGroup $group) use(&$row){
            $row []= $this->renderSpanBr($group->sellUnit->getName() . ' / ' . $group->getCount() . '개', "color: grey");
        });
        $row []= $this->renderSpanBr($this->sellType->shop->shop_name);
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
            'count' => $this->getCount(),
            'src' => $this->getThumbnailSrc()
        ];
    }
}
