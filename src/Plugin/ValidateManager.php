<?php

namespace Xpressengine\Plugins\XeroCommerce\Plugin;

use Xpressengine\Http\Request;
use Validator;

class ValidateManager
{
    public static function getProductValidateRules()
    {
        return [
            'name' => 'required|max:255',
            'sub_name' => 'required',
            'original_price' => 'required',
            'sell_price' => 'required',
            'description' => 'required',
            'stock' => 'required',
            'shop_delivery_id' => 'required'
        ];
    }

    public function productValidate(Request $request)
    {
        Validator::make(
            $request->all(),
            self::getProductValidateRules(),
            [
                'name.required' => '이름 필드는 필수입니다.',
                'sub_name.required' => '간략 소개는 필수입니다.',
                'original_price.required' => '정상 가격은 필수입니다.',
                'sell_price.required' => '정상 가격은 필수입니다.',
                'description.required' => '상품소개는 필수입니다.',
                'stock.required' => '기초재고는 필수입니다.',
                'shop_delivery_id.required' => '배송사선택은 필수입니다.'
            ]
        )->validate();
    }
}
