<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use App\Facades\XeMedia;
use App\Facades\XeStorage;
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
        if(array_has($args, 'user_id')){

            $newShop->users()->sync($args['user_id']);
        }

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
     * @param Shop $query shop
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
     * @param Shop $shop shop
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

    public function addCarrier(array $args, Shop $shop)
    {
        if ($shop->carriers()->wherePivot('id', $args['pivot']['id'])->exists()) {
            $shop->carriers()->wherePivot('id', $args['pivot']['id'])->updateExistingPivot($args['id'], [
                'fare' => $args['pivot']['fare'],
                'addr' => $args['pivot']['addr'],
                'addr_detail' => $args['pivot']['addr_detail'],
                'addr_post' => $args['pivot']['addr_post'],
                'up_to_free' => 0,
                'is_default' => 0
            ]);
        } else {
            $shop->carriers()->attach($args['id'], [
                'fare' => $args['pivot']['fare'],
                'addr' => $args['pivot']['addr'],
                'addr_detail' => $args['pivot']['addr_detail'],
                'addr_post' => $args['pivot']['addr_post'],
                'up_to_free' => 0,
                'is_default' => 0
            ]);
        }
    }

    public function removeCarrier(array $args, Shop $shop)
    {
        $shop->carriers()->wherePivot('id', $args['pivot']['id'])->detach();
    }
}
