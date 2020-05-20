<?php

namespace Xpressengine\Plugins\XeroCommerce\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Agreement;
use Xpressengine\Plugins\XeroCommerce\Models\Carrier;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Plugins\XeroCommerce\Plugin\Resources;
use Xpressengine\User\Models\User;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::storeDefaultShop();
    }

    /**
     * @return void
     */
    public static function storeDefaultShop()
    {
        $userId = Auth::check() ? Auth::id() : User::first()->id;

        $shop = Shop::where('shop_name', Shop::BASIC_SHOP_NAME)->first();
        if (!$shop) {
            $args['user_id'] = $userId;
            $args['shop_type'] = Shop::TYPE_STORE;
            $args['shop_name'] = Shop::BASIC_SHOP_NAME;

            $shopHandler = new ShopHandler();
            $shop = $shopHandler->store($args);

            $shop->carriers()->attach(Carrier::pluck('id'), [
                'fare' => 0,
                'up_to_free' => 0,
                'is_default' => 0
            ]);
        }

        self::storeAgreement(
            'contacts',
            '주문자정보 수집 동의',
            str_replace(
                '<$company_name>',
                $shop->shop_name,
                file_get_contents(Plugin::path('assets/sample/privacy'))
            )
        );
        self::storeAgreement(
            'purchase',
            '구매 동의',
            str_replace(
                '<$company_name>',
                $shop->shop_name,
                file_get_contents(Plugin::path('assets/sample/purchase'))
            )
        );
        self::storeAgreement(
            'privacy',
            '개인정보 수집 및 이용동의',
            str_replace(
                '<$company_name>',
                $shop->shop_name,
                file_get_contents(Plugin::path('assets/sample/privacy'))
            )
        );
        self::storeAgreement(
            'thirdParty',
            '개인정보 제3자 제공/위탁동의',
            str_replace(
                '<$company_name>',
                $shop->shop_name,
                file_get_contents(Plugin::path('assets/sample/thirdParty'))
            )
        );

    }

    public static function storeAgreement($type, $name, $contents)
    {
        $agree = new Agreement();
        $agree->type = $type;
        $agree->name = $name;
        $agree->version = '1.0.0';
        $agree->contents = $contents;
        $agree->save();
    }

}

