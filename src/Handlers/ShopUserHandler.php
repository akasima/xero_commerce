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
}
