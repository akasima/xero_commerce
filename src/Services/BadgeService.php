<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\BadgeHandler;

class BadgeService
{
    /** @var BadgeHandler $handler */
    protected $handler;

    public function __construct()
    {
        $this->handler = app('xero_commerce.badgeHandler');
    }

    public function create(Request $request)
    {
        $args = $request->except('_token');

        $this->handler->store($args);
    }

    public function remove($id)
    {
        $this->handler->destroy($id);
    }
}
