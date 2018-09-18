<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopUserHandler;
use Xpressengine\Plugins\XeroCommerce\Models\ShopUser;
use Xpressengine\User\Models\User;

class ShopUserService
{
    protected $handler;

    /**
     * ShopUserService constructor.
     */
    public function __construct()
    {
        $this->handler = new ShopUserHandler();
    }

    /**
     * @param Request $request request
     * @param int     $shopId  shopId
     *
     * @return ShopUser|void
     */
    public function create(Request $request, $shopId)
    {
        $args = $request->all();

        if (isset($args['user_id']) === false) {
            return;
        }

        $user = User::where('id', $args['user_id'])->first();

        if ($user === null) {
            return;
        }

        $args['shop_id'] = $shopId;

        $newShopUser = $this->handler->store($args);

        return $newShopUser;
    }
}
