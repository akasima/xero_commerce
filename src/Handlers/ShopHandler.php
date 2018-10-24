<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\Shop;

class ShopHandler
{
    /**
     * @param array $args args
     *
     * @return Shop
     */
    public function store(array $args)
    {
        $newShop = new Shop();

        $newShop->fill($args);

        $newShop->save();

        $newShop->users()->sync($args['user_id']);

        return $newShop;
    }

    /**
     * @param array $conditions searchCondition
     *
     * @return Shop
     */
    public function getShopsQuery(array $conditions)
    {
        $query = new Shop();

        $query = $this->makeWhere($conditions, $query);

        return $query;
    }

    /**
     * @param array $conditions searchCondition
     * @param Shop  $query      shop
     *
     * @return Shop
     */
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

    /**
     * @param array $args args
     * @param Shop  $shop shop
     *
     * @return void
     */
    public function update(array $args, Shop $shop)
    {
        $attributes = $shop->getAttributes();
        foreach ($args as $key => $value) {
            if (array_key_exists($key, $attributes)) {
                $shop->{$key} = $value;
            }
        }
        $shop->users()->sync($args['user_id']);

        $shop->save();
    }

    /**
     * @param int $shopId shopId
     *
     * @return Shop|null
     */
    public function getShop($shopId)
    {
        $shop = Shop::where('id', $shopId)->first();

        return $shop;
    }

    /**
     * @param int $shopId shopId
     *
     * @return void
     */
    public function destroy($shopId)
    {
        Shop::where('id', $shopId)->delete();
    }

    public function addDelivery(array $args, Shop $shop)
    {
        if ($shop->deliveryCompanys()->wherePivot('id', $args['pivot']['id'])->exists()) {
            $shop->deliveryCompanys()->wherePivot('id', $args['pivot']['id'])->updateExistingPivot($args['id'], ['delivery_fare' => $args['pivot']['delivery_fare'], 'up_to_free' => 0, 'is_default' => 0]);
        } else {
            $shop->deliveryCompanys()->attach($args['id'], ['delivery_fare' => $args['pivot']['delivery_fare'], 'up_to_free' => 0, 'is_default' => 0]);
        }
    }

    public function removeDelivery(array $args, Shop $shop)
    {
        $shop->deliveryCompanys()->wherePivot('id', $args['pivot']['id'])->detach();
    }
}
