<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;

class OrderItem extends SellSet
{
    protected $table='xero_commerce_order_item';

    const EXCHANGING = 1;
    const EXCHANGED = 2;
    const REFUNDING = 3;
    const REFUNDED = 4;


    public function sellGroups()
    {
        return $this->hasMany(OrderItemGroup::class);
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
        $row []= $this->renderSpanBr($this->sellType->getStore()->store_name);
        return $row;
    }
}