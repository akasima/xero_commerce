<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopHandler;

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

    public function getShops(Request $request)
    {
        $conditions = $request->all();

        $query = $this->handler->getShopsQuery($conditions);

        //TODO pagination 추가
        $shops = $query->get();

        return $shops;
    }

    public function getMyShops($userId)
    {
        $args['user_id'] = $userId;

        $query = $this->handler->getShopsQuery($args);

        $shops = $query->get();

        return $shops;
    }

    public function create(Request $request)
    {
        $args = $request->all();

        $this->handler->store($args);
    }
}
