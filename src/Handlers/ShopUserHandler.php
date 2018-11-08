<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\ShopUser;

class ShopUserHandler
{
    /**
     * @param array $args args
     *
     * @return ShopUser
     */
    public function store(array $args)
    {
        $newShopUser = new ShopUser();

        $newShopUser->fill($args);

        $newShopUser->save();

        return $newShopUser;
    }

    public function getUsersShop($user_id)
    {
        return ShopUser::where('user_id',$user_id)->get();
    }

    public function getShopsUser($shop_id)
    {
        return ShopUser::where('shop_id',$shop_id)->get();
    }
}
