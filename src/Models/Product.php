<?php

namespace Xpressengine\Plugins\XeroStore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Xpressengine\Database\Eloquent\DynamicModel;

class Product extends DynamicModel
{
    use SoftDeletes;

    const DISPLAY_VISIBLE = 1;
    const DISPLAY_HIDDEN = 2;

    const DEAL_ON_SALE = 1;
    const DEAL_PAUSE = 2;
    const DEAL_END = 3;

    protected $table = 'xero_store_products';

    protected $fillable = ['store_id', 'product_code', 'name', 'original_price', 'sell_price', 'discount_percentage',
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
}
