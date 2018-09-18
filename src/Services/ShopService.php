<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Illuminate\Support\Collection;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;

class ShopService
{
    /** @var ShopHandler $handler */
    protected $handler;

    /**
     * ShopService constructor.
     */
    public function __construct()
    {
        $this->handler = app('xero_commerce.shopHandler');
    }

    /**
     * @param Request $request request
     *
     * @return Collection
     */
    public function getShops(Request $request)
    {
        $conditions = $request->all();

        $query = $this->handler->getShopsQuery($conditions);

        //TODO pagination 추가
        $shops = $query->get();

        return $shops;
    }

    /**
     * @param string $userId userId
     *
     * @return Shop
     */
    public function getMyShops($userId)
    {
        $args['user_id'] = $userId;

        $query = $this->handler->getShopsQuery($args);

        $shops = $query->get();

        return $shops;
    }

    /**
     * @param Request $request request
     *
     * @return Shop
     */
    public function create(Request $request)
    {
        $args = $request->all();

        $args['state_approval'] = Shop::APPROVAL_WAITING;

        $newShop = $this->handler->store($args);

        return $newShop;
    }
}
