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

    const TAX_TYPE_TAX = 1;
    const TAX_TYPE_NO = 2;
    const TAX_TYPE_FREE = 3;

    protected $table = 'xero_commerce_products';

    protected $fillable = ['shop_id', 'product_code', 'name', 'original_price', 'sell_price', 'discount_percentage',
        'min_buy_count', 'max_buy_count', 'description', 'badge_id','tax_type', 'state_display', 'state_deal', 'sub_name', 'shop_delivery_id'];

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
     * @return array
     */
    public static function getTaxTypes()
    {
        return [
            self::TAX_TYPE_TAX => '과세',
            self::TAX_TYPE_NO => '비과세',
            self::TAX_TYPE_FREE => '면세',
        ];
    }

    /**
     * @return string
     */
    public function getTaxTypeName()
    {
        $taxType = self::getTaxTypes();

        return $taxType[$this->tax_type];
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
        return $this->sub_name;
    }

    public function getFare()
    {
        // TODO: Implement getFare() method.
        return $this->getDelivery()->delivery_fare;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getShop()
    {
        return $this->shop;
    }

    public function getThumbnailSrc()
    {
        return $this->getImages()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sellUnits()
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

    public function labels()
    {
        return $this->hasManyThrough(Label::class, ProductLabel::class, 'product_id', 'id', 'id', 'label_id');
    }

    public function badge()
    {
        return $this->hasOne(Badge::class, 'id', 'badge_id');
    }

    function getContents()
    {
        // TODO: Implement getContents() method.
        return '';
    }

    public function getStock()
    {
        return $this->sellUnits()->sum('stock');
    }
}
