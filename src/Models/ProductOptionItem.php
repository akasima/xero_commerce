<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Xpressengine\Database\Eloquent\DynamicModel;

class ProductOptionItem extends SellUnit
{
    use SoftDeletes;

    const DISPLAY_VISIBLE = 1;
    const DISPLAY_HIDDEN = 2;

    const DEAL_ON_SALE = 1;
    const DEAL_PAUSE = 2;
    const DEAL_END = 3;

    /**
     * akasima - 2020.04.10.
     * 기본 상품 설정을 위해서 필요한 코드로 보임.
     * 이걸 상수로 만들어야하는지..
     */
    const TYPE_DEFAULT_OPTION = 1;
    const TYPE_ADDITION_ITEM = 1;
    const TYPE_OPTION_ITEM = 1;

    protected $table = 'xero_commerce_product_option_item';

    protected $fillable = ['product_id', 'name', 'value_combination', 'addition_price', 'stock', 'alert_stock',
        'state_display', 'state_deal'];

    protected $casts = [
        'value_combination' => 'json',
    ];

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
     * @return string
     */
    public function getDisplayStateName()
    {
        $displayStates = self::getDisplayStates();

        return $displayStates[$this->state_display];
    }

    /**
     * @return string
     */
    public function getDealStateName()
    {
        $dealState = self::getDealStates();

        return $dealState[$this->state_deal];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function sellType()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function forcedSellType()
    {
        return $this->sellType()->withTrashed()->first();
    }

    public function getName()
    {
        return $this->name;
    }

    public function getInfo()
    {
        return '상세옵션';
    }

    public function getOriginalPrice()
    {
        return $this->forcedSellType()->original_price + $this->addition_price;
    }

    public function getSellPrice()
    {
        return $this->forcedSellType()->sell_price + $this->addition_price;
    }

    public function isDisplay()
    {
        return $this->state_display === self::DISPLAY_VISIBLE;
    }

}
