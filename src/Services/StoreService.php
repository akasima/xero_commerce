<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\StoreHandler;

class StoreService
{
    /** @var StoreHandler $handler */
    protected $handler;

    /**
     * StoreService constructor.
     */
    public function __construct()
    {
        $this->handler = app('xero_commerce.storeHandler');
    }

    public function getStores(Request $request)
    {
        $conditions = $request->all();

        $query = $this->handler->getStoresQuery($conditions);

        //TODO pagination 추가
        $stores = $query->get();

        return $stores;
    }

    public function getMyStores($userId)
    {
        $args['user_id'] = $userId;

        $query = $this->handler->getStoresQuery($args);

        $stores = $query->get();

        return $stores;
    }

    public function create(Request $request)
    {
        $args = $request->all();

        $this->handler->store($args);
    }
}
