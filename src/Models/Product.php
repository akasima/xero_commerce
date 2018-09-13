<?php

namespace Xpressengine\Plugins\XeroStore\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Product extends DynamicModel
{
    const DISPLAY_VISIBLE = 1;
    const DISPLAY_HIDDEN = 2;

    const DEAL_ON_SALE = 1;
    const DEAL_PAUSE = 2;
    const DEAL_END = 3;

    protected $table = 'xero_store_products';

    protected $fillable = ['product_code', 'first_category_id', 'second_category_id', 'third_category_id', 'name',
        'price', 'min_buy_count', 'max_buy_count', 'description', 'state_display', 'state_deal'];

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
