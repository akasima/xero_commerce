<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Xpressengine\Database\Eloquent\DynamicModel;

/**
 * Class ProductVariant
 * @package Xpressengine\Plugins\XeroCommerce\Models
 * @mixin \Eloquent
 */
class ProductVariant extends DynamicModel
{
    use SoftDeletes;

    const DISPLAY_VISIBLE = 1;
    const DISPLAY_HIDDEN = 2;

    const DEAL_ON_SALE = 1;
    const DEAL_PAUSE = 2;
    const DEAL_END = 3;

    protected $table = 'xero_commerce__product_variants';

    protected $fillable = [
        'product_id',
        'name',
        'code',
        'additional_price',
        'stock',
        'alert_stock',
        'state_display',
        'state_deal'
    ];

    protected $casts = [
        'combination_values' => 'json',
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
     * 관계 : 상품
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOriginalPrice()
    {
        return $this->product->original_price + $this->additional_price;
    }

    public function getSellPrice()
    {
        return $this->product->sell_price + $this->additional_price;
    }

    public function isVisible()
    {
        return $this->state_display === self::DISPLAY_VISIBLE;
    }

    public function getDiscountPrice()
    {
        return $this->getOriginalPrice() - $this->getSellPrice();
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->getName(),
            'combination_values' => $this->combination_values,
            'sell_price' => $this->getSellPrice(),
            'additional_price' => $this->additional_price,
            'state_display'=>$this->getDisplayStateName(),
            'state_deal'=>$this->getDealStateName(),
        ];
    }

}
