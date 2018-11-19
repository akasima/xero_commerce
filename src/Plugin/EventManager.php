<?php

namespace Xpressengine\Plugins\XeroCommerce\Plugin;

use Illuminate\Support\Facades\Notification;
use Xpressengine\Plugins\XeroCommerce\Events\NewProductRegisterEvent;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductOptionItemHandler;
use Xpressengine\Plugins\XeroCommerce\Notifications\StockLack;

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
                $productOptionItem = $function($optionItem, $args);

                if($productOptionItem->stock <= $productOptionItem->alert_stock){
                    Notification::route('mail', config('mail.from.address'))
                        ->notify(new StockLack($optionItem));
                }

                return $function($optionItem, $args);
            }
        );
    }
}
