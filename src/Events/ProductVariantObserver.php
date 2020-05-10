<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 23/11/2018
 * Time: 10:47 AM
 */

namespace Xpressengine\Plugins\XeroCommerce\Events;


use Illuminate\Support\Facades\Notification;
use Xpressengine\Plugins\XeroCommerce\Models\ProductVariant;
use Xpressengine\Plugins\XeroCommerce\Notifications\StockLack;

class ProductVariantObserver
{
    public function updated(ProductVariant $productVariant)
    {
        $dirty = $productVariant->getDirty();
        if(isset($dirty['stock'])){
            if ($productVariant->stock <= $productVariant->alert_stock) {
                Notification::route('mail', config('mail.from.address'))
                    ->notify(new StockLack($productVariant));
            }
        }
    }

}
