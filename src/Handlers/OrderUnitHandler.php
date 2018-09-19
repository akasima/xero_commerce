<?php


namespace Xpressengine\Plugins\XeroCommerce\Handlers;


use Xpressengine\Plugins\XeroCommerce\Models\OrderSet;

abstract class OrderUnitHandler
{
    public function getSummary($goodsList = null)
    {
        if(is_null($goodsList)){
            $goodsList = $this->getGoodsList();
        }
        $storeGoods = $goodsList->groupBy(function(OrderSet $goods){
            return $goods->orderable->getStore()->id;
        });
        $origin =
            $goodsList->sum(function (OrderSet $goods) {
                return $goods->getOriginalPrice();
            });
        $sell = $goodsList->sum(function (OrderSet $goods) {
            return $goods->getSellPrice();
        });
        $fare = $storeGoods->sum(function ($storeGoodsList) {
            $totalPrice = $storeGoodsList->sum(function(OrderSet $goods){
                return $goods->getSellPrice();
            });
            return $storeGoodsList->first()->calculateFare($totalPrice);
        });
        $sum = $sell + $fare;
        return [
            'original_price' => $origin,
            'sell_price' => $sell,
            'discount_price'=>$origin-$sell,
            'fare' => $fare,
            'sum' => $sum
        ];
    }

    abstract public function getGoodsList();


}