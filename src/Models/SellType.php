<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

abstract class SellType extends DynamicModel
{
    abstract public function getName();

    abstract public function getInfo();

    abstract public function getFare();

    abstract public function getShop();

    abstract public function getThumbnailSrc();

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
        return $this->morphMany(Image::class, 'imagable');
    }

    public function delivery()
    {
        return $this->belongsTo(ShopDelivery::class, 'shop_delivery_id');
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
            'options' => $this->sellUnits->map(function (SellUnit $sellUnit) {
                return $sellUnit->getJsonFormat();
            }),
            'delivery' => $this->getDelivery()
        ];
    }

    public function getDelivery()
    {
        return $this->delivery->load('company');
    }

    function getImages()
    {
        if ($this->images->count() === 0) return collect([asset('/assets/core/common/img/default_image_1200x800.jpg')]);
        return $this->images->pluck('url');
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
}
