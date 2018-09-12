<?php

namespace Xpressengine\Plugins\XeroStore\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class ProductOptionItem extends DynamicModel
{
    const TYPE_OPTION_ITEM = 1;
    const TYPE_ADDITION_ITEM = 2;

    const DISPLAY_VISIBLE = 1;
    const DISPLAY_HIDDEN = 2;

    const DEAL_ON_SALE = 1;
    const DEAL_PAUSE = 2;
    const DEAL_END = 3;

    protected $table = 'xero_store_product_option_item';

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
}
