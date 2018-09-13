<?php

namespace Xpressengine\Plugins\XeroStore\Plugin;

use Xpressengine\Plugins\XeroStore\Events\NewProductRegisterEvent;

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
