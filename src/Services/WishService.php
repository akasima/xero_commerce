<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\WishHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
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

    public function store(Product $product)
    {
        $this->wishHandler->store($product);
    }

    public function storeMany($collection)
    {
        $this->wishHandler->storeMany($collection);
    }

    public function remove(Product $product)
    {
        $this->wishHandler->remove($product);
    }

    public function toggle(Product $product)
    {
        $existWish = $this->wishHandler->list($product);

        if ($existWish->count() > 0) {
            $this->remove($product);
            return 0;
        } else {
            $this->store($product);
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
