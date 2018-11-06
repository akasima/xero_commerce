<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\SellSet;

abstract class SellSetHandler
{
    public function getSummary($sellSetList = null)
    {
        if (is_null($sellSetList)) {
            $sellSetList = $this->getSellSetList();
        }

        $origin = $sellSetList->sum(function (SellSet $sellSet) {
            return $sellSet->getOriginalPrice();
        });

        $sell = $sellSetList->sum(function (SellSet $sellSet) {
            return $sellSet->getSellPrice();
        });

        $fare = $sellSetList->sum(function (SellSet $sellSet) {
            return $sellSet->getFare();
        });

        $sum = $sell + $fare;

        return [
            'original_price' => $origin,
            'sell_price' => $sell,
            'discount_price' => $origin - $sell,
            'fare' => $fare,
            'sum' => $sum
        ];
    }

    abstract public function getSellSetList();
}
