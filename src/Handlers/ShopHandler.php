<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\Shop;

class ShopHandler
{
    public function store(array $args)
    {
        $newShop = new Shop();

        $newShop->fill($args);

        $newShop->save();
    }

    public function getShopsQuery(array $conditions)
    {
        $query = new Shop();

        $query = $this->makeWhere($conditions, $query);

        return $query;
    }

    private function makeWhere(array $conditions, Shop $query)
    {
        if (isset($conditions['user_id'])) {
            $query = $query->where('user_id', $conditions['user_id']);
        }

        if (isset($conditions['shop_name'])) {
            $query = $query->where('shop_name', 'like', '%' . $conditions['shop_name'] . '%');
        }

        return $query;
    }
}
