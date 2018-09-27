<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Tag\Tag;

class Product extends SellType
{
    use SoftDeletes;

    const DISPLAY_VISIBLE = 1;
    const DISPLAY_HIDDEN = 2;

    const DEAL_ON_SALE = 1;
    const DEAL_PAUSE = 2;
    const DEAL_END = 3;

    protected $table = 'xero_commerce_products';

    protected $fillable = ['shop_id', 'product_code', 'name', 'original_price', 'sell_price', 'discount_percentage',
        'min_buy_count', 'max_buy_count', 'description', 'state_display', 'state_deal'];

    /**
     * @return array
     */
    public static function getDisplayStates()
    {
        return [
            self::DISPLAY_VISIBLE => '출력',
            self::DISPLAY_HIDDEN => '숨김'
        ];
    }

    /**
     * @return array
     */
    public static function getDealStates()
    {
        return [
            self::DEAL_ON_SALE => '판매중',
            self::DEAL_PAUSE => '판매 일시 중지',
            self::DEAL_END => '거래 종료',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productOption()
    {
        return $this->hasMany(ProductOptionItem::class, 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getInfo()
    {
        return $this->description;
    }

    public function getFare()
    {
        // TODO: Implement getFare() method.
        return 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getShop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function getThumbnailSrc()
    {
        return 'https://www.xpressengine.io/plugins/official_homepage/assets/theme/img/feature_02.jpg';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderUnits()
    {
        return $this->hasMany(ProductOptionItem::class);
    }

    /**
     * @return callable
     */
    public function getCountMethod()
    {
        return function ($sellGroupCollection) {
            return $sellGroupCollection->sum(function (SellGroup $sellGroup) {
                return $sellGroup->getCount();
            });
        };
    }

    /**
     * @return callable
     */
    public function getOriginalPriceMethod()
    {
        return function ($sellGroupCollection) {
            return $sellGroupCollection->sum(function (SellGroup $sellGroup) {
                return $sellGroup->getOriginalPrice();
            });
        };
    }

    /**
     * @return callable
     */
    public function getSellPriceMethod()
    {
        return function ($sellGroupCollection) {
            return $sellGroupCollection->sum(function (SellGroup $sellGroup) {
                return $sellGroup->getSellPrice();
            });
        };
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'taggables', 'taggable_id', 'tag_id');
    }

    public function getSlug()
    {
        return $this->slug->slug;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function slug()
    {
        return $this->belongsTo(ProductSlug::class, 'id', 'target_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productSlug()
    {
        return $this->hasOne(ProductSlug::class, 'target_id');
    }
}
