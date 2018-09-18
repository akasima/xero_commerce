<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Xpressengine\Plugins\XeroCommerce\Models\Store;

class StoreHandler
{
    public function store(array $args)
    {
        $newStore = new Store();

        $newStore->fill($args);

        $newStore->save();
    }

    public function getStoresQuery(array $conditions)
    {
        $query = new Store();

        $query = $this->makeWhere($conditions, $query);

        return $query;
    }

    private function makeWhere(array $conditions, Store $query)
    {
        if (isset($conditions['user_id'])) {
            $query = $query->where('user_id', $conditions['user_id']);
        }

        if (isset($conditions['store_name'])) {
            $query = $query->where('store_name', 'like', '%' . $conditions['store_name'] . '%');
        }

        return $query;
    }
}
