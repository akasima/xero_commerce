<?php

namespace Xpressengine\Plugins\XeroCommerce\Plugin;

use Xpressengine\Plugins\XeroCommerce\Events\NewProductRegisterEvent;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductOptionItemHandler;

class EventManager
{
    public static function listenEvents()
    {
        self::newRegisterProductListen();
        self::checkProductOptionStockListen();
    }

    public static function newRegisterProductListen()
    {
        \Event::listen(NewProductRegisterEvent::class, function ($productData) {
            \Log::info($productData->product->id);
        });
    }

    public static function checkProductOptionStockListen()
    {
        intercept(
            sprintf('%s@update', ProductOptionItemHandler::class),
            'XeroCommerceProductOptionItemUpdate',
            function ($function, $optionItem, $args) {
                \Log::info('productOptionItem update');

                return $function($optionItem, $args);
            }
        );
    }
}
