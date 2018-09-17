<?php

namespace Xpressengine\Plugins\XeroCommerce\Plugin;

use Xpressengine\Plugins\XeroCommerce\Events\NewProductRegisterEvent;

class EventManager
{
    public static function listenEvents()
    {
        self::newRegisterProductListen();
    }

    public static function newRegisterProductListen()
    {
        \Event::listen(NewProductRegisterEvent::class, function ($productData) {
            \Log::info($productData->product->id);
        });
    }
}
