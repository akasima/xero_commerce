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
        'original_price' => 'nullable',
        'sell_price' => 'required',
        'description' => 'required',
        'stock' => 'required',
        'shop_carrier_id' => 'required'
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
                'original_price.numeric'=>'정상 가격은 숫자로 입력해주세요.',
                'sell_price.required' => '판매 가격은 필수입니다.',
                'sell_price.numeric'=>'판매 가격은 숫자로 입력해주세요.',
                'description.required' => '상품소개는 필수입니다.',
                'stock.required' => '기초재고는 필수입니다.',
                'shop_carrier_id.required' => '배송사선택은 필수입니다.'
            ]
        )->validate();
    }

    public static function getShopValidateRules()
    {
        return [
            'shop_name' => 'required|max:255',
            'shop_eng_name' => 'required',
            'user_id' => 'required',
            'shipping_info' => 'required',
            'as_info' => 'required'
        ];
    }

    public function shopValidate(Request $request)
    {
        Validator::make(
            $request->all(),
            self::getShopValidateRules(),
            [
                'shop_name.required' => '이름 필드는 필수입니다.',
                'shop_eng_name.required' => '영어 이름 필드는 필수입니다.',
                'user_id.required' => '관리자 아이디는 적어도 하나가 필요합니다.',
                'shipping_info.required' => '배송정보는 필수입니다.',
                'as_info' => '반품/교환정보는 필수입니다.'
            ]
        )->validate();
    }
}
