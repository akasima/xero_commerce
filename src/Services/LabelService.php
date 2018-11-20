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

    public function create(Request $request)
    {
        $args = $request->except('_token');

        $this->handler->store($args);
    }

    public function update(Request $request, $label)
    {
        $args = $request->except('_token');

        $this->handler->update($label, $args);
    }

    public function remove($id)
    {
        $this->handler->destroy($id);
    }

    public function createProductLabel($productId, Request $request)
    {
        $labels = $request->get('labels', '');

        if ($labels === '') {
            return;
        }

        $this->handler->storeProductLabel($productId, $labels);
    }

    public function updateProductLabel($productId, Request $request)
    {
        $labels = $request->get('labels', '');

        if ($labels === '') {
            return;
        }

        $this->handler->destroyProductLabel($productId);
        $this->handler->storeProductLabel($productId, $labels);
    }
}
