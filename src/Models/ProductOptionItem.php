<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Xpressengine\Database\Eloquent\DynamicModel;

class ProductOptionItem extends OrderUnit
{
    use SoftDeletes;

    const TYPE_DEFAULT_OPTION = 1;
    const TYPE_OPTION_ITEM = 2;
    const TYPE_ADDITION_ITEM = 3;

    const DISPLAY_VISIBLE = 1;
    const DISPLAY_HIDDEN = 2;

    const DEAL_ON_SALE = 1;
    const DEAL_PAUSE = 2;
    const DEAL_END = 3;

    protected $table = 'xero_commerce_product_option_item';

    protected $fillable = ['product_id', 'option_type', 'name', 'addition_price', 'stock', 'alert_stock',
        'state_display', 'state_deal'];
    public $timestamps = false;

    /**
     * @return array
     */
    public function getOptionTypes()
    {
        return [
            self::TYPE_OPTION_ITEM => '옵션 상품',
            self::TYPE_ADDITION_ITEM => '추가 상품',
        ];
    }

    /**
     * @return array
     */
    public function getDisplayStates()
    {
        return [
            self::DISPLAY_VISIBLE => '출력',
            self::DISPLAY_HIDDEN => '숨김'
        ];
    }

    /**
     * @return array
     */
    public function getDealStates()
    {
        return [
            self::DEAL_ON_SALE => '판매중',
            self::DEAL_PAUSE => '판매 일시 중지',
            self::DEAL_END => '거래 종료',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function getInfo()
    {
        return [
            $this->product->name,
            $this->name,
            '쇼핑몰 : '.$this->getShop()->store_name
        ];
    }

    public function getOriginalPrice()
    {
        return $this->product->original_price + $this->addition_price;
    }

    public function getSellPrice()
    {
        return $this->product->sell_price + $this->addition_price;
    }

    public function getOptionList()
    {
        return $this->product->options;
    }

    public function getThumbnailSrc()
    {
        return 'https://www.xpressengine.io/plugins/official_homepage/assets/theme/img/feature_02.jpg';
    }

    public function getDescription()
    {
        return $this->product->description;
    }

    public function getShop()
    {
        return $this->product->shop;
    }

    public function getFare()
    {
        // TODO: Implement getFare() method.
    }
}
