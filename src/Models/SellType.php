<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use App\Facades\XeMedia;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Database\Eloquent\DynamicModel;

abstract class SellType extends DynamicModel
{
    abstract public function getName();

    abstract public function getInfo();

    abstract public function getFare();

    abstract public function getShop();

    abstract public function getThumbnailSrc();

    public function wishs()
    {
        return $this->morphMany(Wish::class, 'type');
    }

    public function userWish()
    {
        return $this->wishs()->where('user_id', Auth::id())->first();
    }

    public function carts()
    {
        return $this->morphMany(Cart::class, 'type');
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'type');
    }

    public function images()
    {
        return $this->morphToMany(\Xpressengine\Media\Models\Image::class, 'imagable', 'xero_commerce_images');
    }

    public function delivery()
    {
        return $this->belongsTo(ShopDelivery::class, 'shop_delivery_id');
    }

    protected function visibleSellUnits()
    {
        return $this->sellUnits->filter(function(SellUnit $sellUnit){
            return $sellUnit->isDisplay();
        });
    }

    public function getJsonFormat()
    {
        return [
            'id' => $this->id,
            'mainImage' => $this->getThumbnailSrc(),
            'images' => $this->getImages(),
            'contents' => $this->getContents(),
            'data' => $this,
            'shop' => $this->getShop(),
            'options' => $this->getAvailableOptions(),
            'delivery' => $this->getDelivery(),
            'url' => $this->slugUrl()
        ];
    }

    public function getAvailableOptions()
    {
        return $this->visibleSellUnits()->map(function (SellUnit $sellUnit) {
            return $sellUnit->getJsonFormat();
        });
    }

    public function getDelivery()
    {
        return $this->delivery->load('company');
    }

    function getImages()
    {
        if ($this->images->count() === 0) {
            return collect([asset('/assets/core/common/img/default_image_1200x800.jpg')]);
        }

        return $this->images->map(function ($item) {
            return XeMedia::images()->getThumbnail($item, 'widen', 'B')->url();
        });
    }

    abstract function getContents();

    abstract function sellUnits();

    abstract function getSlug();

    /**
     * @return callable
     */
    abstract function getCountMethod();

    /**
     * @return callable
     */
    abstract function getOriginalPriceMethod();

    /**
     * @return callable
     */
    abstract function getSellPriceMethod();

    abstract function slugUrl();

    abstract function renderForSellSet(SellSet $sellSet);

    abstract function isDelivered();
}
