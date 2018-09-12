<?php

namespace Xpressengine\Plugins\XeroStore\Plugin;

use XeRegister;
use Route;

class Resources
{
    public static function registerRoute()
    {
        Route::settings('xero_store', function () {
            Route::group([
                'namespace' => 'Xpressengine\\Plugins\\XeroStore\\Controllers\\Settings'
            ], function () {
                Route::get('/', ['as' => 'xero_store::setting.product.index', 'uses' => 'ProductController@index',
                    'settings_menu' => 'xero_store.product.list']);
            });
        });
    }

    public static function bindClasses()
    {

    }

    /**
     * 관리자에 메뉴 등록
     *
     * @return void
     */
    public static function registerSettingMenu()
    {
        $menus = array_merge(
            [
                'xero_store' => [
                    'title' => '쇼핑몰 관리',
                    'display' => true,
                    'description' => '',
                    'ordering' => 10000
                ],
            ],
            static::menuConfigure(),
            static::menuOrder(),
            static::menuProduct()
        );

        foreach ($menus as $id => $menu) {
            XeRegister::push('settings/menu', $id, $menu);
        }
    }

    /**
     * @return array
     */
    private static function menuProduct()
    {
        return [
            'xero_store.product' => [
                'title' => '상품관리',
                'display' => true,
                'description' => '',
                'ordering' => 10001
            ],
            'xero_store.product.list' => [
                'title' => '전체 상품목록',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
            'xero_store.product.category' => [
                'title' => '분류 관리',
                'display' => true,
                'description' => '',
                'ordering' => 100012
            ],
            'xero_store.product.create' => [
                'title' => '상품 등록',
                'display' => true,
                'description' => '',
                'ordering' => 100013
            ],
        ];
    }

    /**
     * @return array
     */
    private static function menuOrder()
    {
        return [
            'xero_store.order' => [
                'title' => '주문',
                'display' => true,
                'description' => '',
                'ordering' => 10002
            ],
            'xero_store.order.index' => [
                'title' => '전체 주문내역',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
        ];
    }

    /**
     * @return array
     */
    private static function menuConfigure()
    {
        return [
            'xero_store.configure' => [
                'title' => '환경설정',
                'display' => true,
                'description' => '',
                'ordering' => 10003
            ],

            'xero_store.configure.shopInfo' => [
                'title' => '정보 등록',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
        ];
    }
}
