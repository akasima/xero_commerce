<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 23/11/2018
 * Time: 10:47 AM
 */

namespace Xpressengine\Plugins\XeroCommerce\Events;


use Illuminate\Support\Facades\Notification;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Notifications\StockLack;

class ProductOptionItemObserver
{
    public function updated(ProductOptionItem $optionItem)
    {
        $dirty = $optionItem->getDirty();
        if(isset($dirty['stock'])){
            if ($optionItem->stock <= $optionItem->alert_stock) {
                Notification::route('mail', config('mail.from.address'))
                    ->notify(new StockLack($optionItem));
            }
        }
    }

}
