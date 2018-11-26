<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\WishHandler;
use Xpressengine\Plugins\XeroCommerce\Models\SellType;
use Xpressengine\Plugins\XeroCommerce\Models\Wish;

class WishService
{
    /**
     * @var WishHandler
     */
    protected $wishHandler;

    public function __construct()
    {
        $this->wishHandler = app('xero_commerce.wishHandler');
    }

    public function store(SellType $sellType)
    {
        $this->wishHandler->store($sellType);
    }

    public function storeMany($collection)
    {
        $this->wishHandler->storeMany($collection);
    }

    public function remove(SellType $sellType)
    {
        $this->wishHandler->remove($sellType);
    }

    public function toggle(SellType $sellType)
    {
        $existWish = $this->wishHandler->list($sellType);

        if ($existWish->count() > 0) {
            $this->remove($sellType);
            return 0;
        } else {
            $this->store($sellType);
            return 1;
        }
    }

    public function get()
    {
        return $this->wishHandler->list();
    }

    public function removeByModel(Wish $wish)
    {
        $this->wishHandler->removeByModel($wish);
    }

    public function removeMany(Request $request)
    {
        $this->wishHandler->removeMany($request->get('ids'));
    }
}
