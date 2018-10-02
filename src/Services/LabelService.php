<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\LabelHandler;

class LabelService
{
    /** @var LabelHandler $handler */
    protected $handler;

    /**
     * LabelService constructor.
     */
    public function __construct()
    {
        $this->handler = app('xero_commerce.labelHandler');
    }

    public function createProductLabel($productId, Request $request)
    {
        $labels = $request->get('labels');

        $this->handler->storeProductLabel($productId, $labels);
    }

    public function editProductLabel($productId, Request $request)
    {
        $labels = $request->get('labels');

        $this->handler->destroyProductLabel($productId);
        $this->handler->storeProductLabel($productId, $labels);
    }
}
