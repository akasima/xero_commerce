<?php

namespace Xpressengine\Plugins\XeroCommerce\Plugin;

use App\Facades\XeCategory;
use App\Facades\XeConfig;
use App\Facades\XeInterception;
use App\Facades\XeLang;
use App\Facades\XeMedia;
use App\Facades\XeStorage;
use Faker\Factory;
use XeRegister;
use XeDB;
use XeMenu;
use Route;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Http\Request;
use Xpressengine\Permission\Grant;
use Xpressengine\Plugins\CkEditor\Editors\CkEditor;
use Xpressengine\Plugins\XeroCommerce\Controllers\Settings\ProductController;
use Xpressengine\Plugins\XeroCommerce\Handlers\BadgeHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\LabelHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductCategoryHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductOptionItemHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopHandler;
use Xpressengine\Plugins\XeroCommerce\Middleware\AgreementMiddleware;
use Xpressengine\Plugins\XeroCommerce\Models\Agreement;
use Xpressengine\Plugins\XeroCommerce\Models\Badge;
use Xpressengine\Plugins\XeroCommerce\Models\DeliveryCompany;
use Xpressengine\Plugins\XeroCommerce\Models\Image;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCategory;
use Xpressengine\Plugins\XeroCommerce\Models\ProductLabel;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\Plugins\XeroCommerce\Models\SellType;
use Xpressengine\Plugins\XeroCommerce\Models\SellUnit;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Plugins\XeroCommerce\Services\ProductSlugService;
use Xpressengine\Routing\InstanceRoute;
use Xpressengine\User\Models\User;
use Xpressengine\XePlugin\XeroPay\Inicis\InicisHandler;
use Xpressengine\XePlugin\XeroPay\LG\LGHandler;
use Xpressengine\XePlugin\XeroPay\PaymentHandler;
use Xpressengine\XePlugin\XeroPay\Test\TestHandler;

class Resources
{
    /**
     * @return void
     */
    public static function setCanNotUseXeroCommercePrefixRoute()
    {
        config(['xe.routing' => array_merge(
            config('xe.routing'),
            ['xero_commerce' => Plugin::XERO_COMMERCE_URL_PREFIX]
        )]);
    }

    public static function setCanUseXeroCommercePrefixRoute()
    {
        $routing = config('xe.routing');

        array_forget($routing, 'xero_commerce');

        config(['xe.routing' => $routing]);
    }

    public static function setThumnailDimensionSEtting()
    {
        config(['xe.media.thumbnail.dimensions' => array_merge(
            config('xe.media.thumbnail.dimensions'),
            [
                'T' => ['width' => 50, 'height' => 40],
                'B' => ['width' => 500 , 'height'=> 500]
            ]
        )]);
    }

    /**
     * @return bool
     */
    public static function isUsedXeroCommercePrefix()
    {
        if (InstanceRoute::where('url', Plugin::XERO_COMMERCE_URL_PREFIX)->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function defaultSitemapSetting()
    {
        //TODO 모듈이 있는지 확인해서 등록 안하는 옵션 추가 필요

        self::setCanUseXeroCommercePrefixRoute();

        $defaultMenu = self::createDefaultMenu();
        self::storeConfigData('mainMenuId', $defaultMenu['id']);

        $mainWidgetboxPageId = self::createDefaultMainWidgetboxPage($defaultMenu);
        self::storeConfigData('mainPageId', $mainWidgetboxPageId);

        self::createDefaultCategoryModule($defaultMenu);
        self::setDefaultThemeConfig($defaultMenu);
        self::createXeroStoreDirectLink();
        self::settingWidget($mainWidgetboxPageId);

        self::setCanNotUseXeroCommercePrefixRoute();
    }

    protected static function settingWidget($mainWidgetboxPageId)
    {
        $widgetboxHandler = app('xe.widgetbox');

        $widgetboxPrefix = 'widgetpage-';
        $id = $widgetboxPrefix.$mainWidgetboxPageId;

        $categoryId = \XeConfig::get(Plugin::getId())->get('categoryId', '');
        $initCategories = CategoryItem::where('category_id', $categoryId)->pluck('id')->toArray();

        if (empty($initCategories) == false) {
            $initCategories = implode(',', $initCategories);
        }

        $eventWidget['left_product_id'] = '1';
        $eventWidget['center_up_product_id'] = '1';
        $eventWidget['center_down_product_id'] = '1';
        $eventWidget['right_product_id'] = '1';
        $eventWidget['@attributes'] = [
            'id' => 'widget/xero_commerce@event_widget',
            'title' => 'Event',
            'skin-id' => 'widget/xero_commerce@event_widget/skin/xero_commerce@event_widget_common_skin'
        ];

        $labelWidget['label_id'] = '1';
        $labelWidget['category_item_id'] = $initCategories;
        $labelWidget['product_id'] = '1';
        $labelWidget['@attributes'] = [
            'id' => 'widget/xero_commerce@label_product_widget',
            'title' => 'Label',
            'skin-id' => 'widget/xero_commerce@label_product_widget/skin/xero_commerce@label_widget_common_skin'
        ];

        $initValue['grid'] = ['md' => '12'];
        $initValue['rows'] = [];
        $initValue['widgets'] = [$eventWidget, $labelWidget];

        $value[] = $initValue;

        $widgetboxHandler->update($id, ['content' => [$value]]);
    }

    protected static function createDefaultMenu()
    {
        $menuTitle = 'XeroCommerce';
        $menuDescription = 'XeroCommerce 메뉴 입니다.';

        //TODO 테마 자동 설정 필요
        \XeConfig::add('theme.settings.theme/xero_commerce@xero_commerce_theme_default', []);
        \XeConfig::add('theme.settings.theme/xero_commerce@xero_commerce_theme_default.0', []);
        $desktopTheme = 'theme/xero_commerce@xero_commerce_theme_default.0';
        $mobileTheme = 'theme/xero_commerce@xero_commerce_theme_default.0';

        XeDB::beginTransaction();

        try {
            $menu = XeMenu::createMenu([
                'title' => $menuTitle,
                'description' => $menuDescription,
                'site_key' => \XeSite::getCurrentSiteKey(),
            ]);

            XeMenu::setMenuTheme($menu, $desktopTheme, $mobileTheme);

            app('xe.permission')->register($menu->getKey(), XeMenu::getDefaultGrant());
        } catch (\Exception $e) {
            XeDB::rollback();

            throw $e;
        }

        XeDB::commit();

        return $menu;
    }

    protected static function createDefaultMainWidgetboxPage($defaultMenu)
    {
        //TODO 리펙토링
        $inputs['parent'] = $defaultMenu['id'];
        $inputs['siteKey'] = $defaultMenu['siteKey'];
        $inputs['itemTitle'] = self::getTranslationKey('MainPage');
        $inputs['itemUrl'] = Plugin::XERO_COMMERCE_URL_PREFIX;
        $inputs['itemDescription'] = '메인 페이지에 출력될 위젯을 설정할 수 있습니다.';
        $inputs['itemTarget'] = '_self';
        $inputs['selectedType'] = 'widgetpage@widgetpage';
        $inputs['itemOrdering'] = 0;
        $inputs['itemActivated'] = 1;

        $itemInputKeys = [
            'itemId',
            'parent',
            'itemTitle',
            'itemUrl',
            'itemDescription',
            'itemTarget',
            'selectedType',
            'itemOrdering',
            'itemActivated',
            'basicImage',
            'hoverImage',
            'selectedImage',
        ];

        $itemInput = array_only($inputs, $itemInputKeys);
        $menuTypeInput = array_except($inputs, $itemInputKeys);

        XeDB::beginTransaction();

        try {
            $desktopTheme = null;
            $mobileTheme = null;

            $itemInput['parent'] = $itemInput['parent'] === $defaultMenu->getKey() ? null : $itemInput['parent'];
            $item = XeMenu::createItem($defaultMenu, [
                'title' => $itemInput['itemTitle'],
                'url' => trim($itemInput['itemUrl'], " \t\n\r\0\x0B/"),
                'description' => $itemInput['itemDescription'],
                'target' => $itemInput['itemTarget'],
                'type' => $itemInput['selectedType'],
                'ordering' => $itemInput['itemOrdering'],
                'activated' => isset($itemInput['itemActivated']) ? $itemInput['itemActivated'] : 0,
                'parent_id' => $itemInput['parent']
            ], $menuTypeInput);

            XeMenu::setMenuItemTheme($item, $desktopTheme, $mobileTheme);
            app('xe.permission')->register(XeMenu::permKeyString($item), new Grant, $defaultMenu->site_key);
        } catch (\Exception $e) {
            XeDB::rollback();

            throw $e;
        }

        XeDB::commit();

        return $item->id;
    }

    protected static function createDefaultCategoryModule($defaultMenu)
    {
        $config = \XeConfig::get(Plugin::getId());
        $categoryId = $config->get('categoryId', '');

        if ($categoryId === '') {
            return;
        }

        $itemInputKeys = [
            'itemId',
            'parent',
            'itemTitle',
            'itemUrl',
            'itemDescription',
            'itemTarget',
            'selectedType',
            'itemOrdering',
            'itemActivated',
            'basicImage',
            'hoverImage',
            'selectedImage',
        ];

        $initCategories = CategoryItem::where('category_id', $categoryId)->get();
        foreach ($initCategories as $idx => $category) {
            $inputs = [];
            $inputs['parent'] = $defaultMenu['id'];
            $inputs['siteKey'] = $defaultMenu['siteKey'];
            $inputs['itemTitle'] = self::getTranslationKey(xe_trans($category['word']));
            $inputs['itemUrl'] = 'category' . ($idx + 1);
            $inputs['itemDescription'] = '기본 상품 페이지입니다.';
            $inputs['itemTarget'] = '_self';
            $inputs['selectedType'] = 'xero_commerce@xero_commerce_module';
            $inputs['itemOrdering'] = 0;
            $inputs['itemActivated'] = 1;
            $inputs['categoryItemId'] = $category['id'];
            $inputs['categoryItemDepth'] = 1;

            $itemInput = array_only($inputs, $itemInputKeys);
            $menuTypeInput = array_except($inputs, $itemInputKeys);

            XeDB::beginTransaction();

            try {
                $desktopTheme = null;
                $mobileTheme = null;

                $itemInput['parent'] = $itemInput['parent'] === $defaultMenu->getKey() ? null : $itemInput['parent'];
                $item = XeMenu::createItem($defaultMenu, [
                    'title' => $itemInput['itemTitle'],
                    'url' => trim($itemInput['itemUrl'], " \t\n\r\0\x0B/"),
                    'description' => $itemInput['itemDescription'],
                    'target' => $itemInput['itemTarget'],
                    'type' => $itemInput['selectedType'],
                    'ordering' => $itemInput['itemOrdering'],
                    'activated' => isset($itemInput['itemActivated']) ? $itemInput['itemActivated'] : 0,
                    'parent_id' => $itemInput['parent']
                ], $menuTypeInput);

                XeMenu::setMenuItemTheme($item, $desktopTheme, $mobileTheme);
                app('xe.permission')->register(XeMenu::permKeyString($item), new Grant, $defaultMenu->site_key);
            } catch (\Exception $e) {
                XeDB::rollback();

                throw $e;
            }

            XeDB::commit();
        }
    }

    public static function setDefaultThemeConfig($defaultMenu)
    {
        $config['logo_title'] = 'XeroCommerce';
        $config['gnb'] = $defaultMenu['id'];

        app('xe.theme')->setThemeConfig('theme/xero_commerce@xero_commerce_theme_default.0', $config);
    }

    private static function getTranslationKey($title)
    {
        $key = Plugin::XERO_COMMERCE_PREFIX . '::' . app('xe.keygen')->generate();

        XeLang::save($key, 'ko', $title, false);

        return $key;
    }

    protected static function createXeroStoreDirectLink()
    {
        $menuId = \XeConfig::get('site.default')['defaultMenu'];
        $defaultMenu = XeMenu::menus()->find($menuId);

        //TODO 리펙토링
        $inputs['parent'] = $menuId;
        $inputs['siteKey'] = $defaultMenu['siteKey'];
        $inputs['itemTitle'] = self::getTranslationKey('XeroCommerce');
        $inputs['itemUrl'] = Plugin::XERO_COMMERCE_URL_PREFIX;
        $inputs['itemDescription'] = '메인 페이지에 추가된 쇼핑몰 링크입니다.';
        $inputs['itemTarget'] = '_self';
        $inputs['selectedType'] = 'xpressengine@directLink';
        $inputs['itemOrdering'] = 0;
        $inputs['itemActivated'] = 1;

        $itemInputKeys = [
            'itemId',
            'parent',
            'itemTitle',
            'itemUrl',
            'itemDescription',
            'itemTarget',
            'selectedType',
            'itemOrdering',
            'itemActivated',
            'basicImage',
            'hoverImage',
            'selectedImage',
        ];

        $itemInput = array_only($inputs, $itemInputKeys);
        $menuTypeInput = array_except($inputs, $itemInputKeys);

        XeDB::beginTransaction();

        try {
            $desktopTheme = null;
            $mobileTheme = null;

            $itemInput['parent'] = $itemInput['parent'] === $defaultMenu->getKey() ? null : $itemInput['parent'];
            $item = XeMenu::createItem($defaultMenu, [
                'title' => $itemInput['itemTitle'],
                'url' => trim($itemInput['itemUrl'], " \t\n\r\0\x0B/"),
                'description' => $itemInput['itemDescription'],
                'target' => $itemInput['itemTarget'],
                'type' => $itemInput['selectedType'],
                'ordering' => $itemInput['itemOrdering'],
                'activated' => isset($itemInput['itemActivated']) ? $itemInput['itemActivated'] : 0,
                'parent_id' => $itemInput['parent']
            ], $menuTypeInput);

            XeMenu::setMenuItemTheme($item, $desktopTheme, $mobileTheme);
            app('xe.permission')->register(XeMenu::permKeyString($item), new Grant, $defaultMenu->site_key);
        } catch (\Exception $e) {
            XeDB::rollback();

            throw $e;
        }

        XeDB::commit();
    }

    /**
     * @return void
     */
    public static function registerRoute()
    {
        Route::group([
            'namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers',
            'prefix' => Plugin::XERO_COMMERCE_URL_PREFIX,
            'middleware' => ['web']
        ], function () {
            Route::get('/cart', [
                'uses' => 'CartController@index',
                'as' => 'xero_commerce::cart.index'
            ]);
            Route::get('/cart/draw/{cart}', [
                'uses' => 'CartController@draw',
                'as' => 'xero_commerce::cart.draw'
            ]);
            Route::get('/cart/draw-list', [
                'uses' => 'CartController@drawList',
                'as' => 'xero_commerce::cart.drawList'
            ]);
            Route::get('/cart/change/{cart}', [
                'uses' => 'CartController@change',
                'as' => 'xero_commerce::cart.change'
            ]);
            Route::get('/cart/list', [
                'uses' => 'CartController@list',
                'as' => 'xero_commerce::cart.list'
            ]);
            Route::get('/cart/summary', [
                'uses' => 'CartController@summary',
                'as' => 'xero_commerce::cart.summary'
            ]);
            Route::post('/product/cart/{product}', [
                'uses' => 'ProductController@cartAdd',
                'as' => 'xero_commerce::product.cart'
            ]);

            Route::get('/order', [
                'uses' => 'OrderController@index',
                'as' => 'xero_commerce::order.index'
            ])->middleware(['auth', AgreementMiddleware::class]);
            Route::post('/order', [
                'uses' => 'OrderController@register',
                'as' => 'xero_commerce::order.register'
            ]);
            Route::get('/order/register', [
                'uses' => 'OrderController@registerAgain',
                'as' => 'xero_commerce::order.register.again'
            ])->middleware(['auth', AgreementMiddleware::class]);
            Route::get('/order/detail/{order}', [
                'uses' => 'OrderController@detail',
                'as' => 'xero_commerce::order.detail'
            ]);
            Route::get('/order/list', [
                'uses' => 'OrderController@list',
                'as' => 'xero_commerce::order.list'
            ]);
            Route::post('/order/list/{page}', [
                'uses' => 'OrderController@listJson',
                'as' => 'xero_commerce::order.page'
            ]);
            Route::post('/order/pay/{order}', [
                'uses' => 'OrderController@pay',
                'as' => 'xero_commerce::order.pay'
            ]);
            Route::post('/order/success/{order}', [
                'uses' => 'OrderController@success',
                'as' => 'xero_commerce::order.success'
            ]);
            Route::get('/order/fail/{order}', [
                'uses' => 'OrderController@fail',
                'as' => 'xero_commerce::order.fail'
            ]);
            Route::get('/order/service/{as}/{order}/{orderItem}', [
                'uses' => 'OrderController@afterService',
                'as' => 'xero_commerce::order.as'
            ]);

            Route::post('/order/service/{type}/{orderItem}', [
                'uses' => 'OrderController@asRegister',
                'as' => 'xero_commerce::order.as.register'
            ]);

            Route::get('/agreement/contacts', [
                'uses' => 'AgreementController@contacts',
                'as' => 'xero_commerce::agreement.contacts'
            ]);
            Route::post('/agreement/contacts', [
                'uses' => 'AgreementController@saveContacts',
                'as' => 'xero_commerce::agreement.contacts.save'
            ]);
            Route::post('/agreement/order/{order}', [
                'uses' => 'AgreementController@saveOrderAgree',
                'as' => 'xero_commerce::agreement.order.save'
            ]);
            Route::post('/agreement/cancel/order/{order}', [
                'uses' => 'AgreementController@cancelOrderAgree',
                'as' => 'xero_commerce::agreement.order.cancel'
            ]);

            Route::get('/no-delivery', [
                'as' => 'xero_commerce::no-delivery',
                'uses' => 'DeliveryController@index'
            ]);

            Route::get('/{strSlug}', [
                'as' => 'xero_commerce::product.show',
                'uses' => 'ProductController@show'
            ]);
        });


        Route::settings('xero_commerce', function () {
            Route::group([
                'namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers\\Settings'
            ], function () {
                //상품관리
                Route::group(['prefix' => 'products'], function () {
                    Route::get('/', ['as' => 'xero_commerce::setting.product.index',
                        'uses' => 'ProductController@index',
                        'settings_menu' => 'xero_commerce.product.list']);
                    Route::get('/create', ['as' => 'xero_commerce::setting.product.create',
                        'uses' => 'ProductController@create', 'settings_menu' => 'xero_commerce.product.create']);
                    Route::post('/store', ['as' => 'xero_commerce::setting.product.store',
                        'uses' => 'ProductController@store']);

                    Route::post('/option/store', ['as' => 'xero_commerce::setting.product.option.store',
                        'uses' => 'ProductOptionController@store']);

                    Route::get('/{productId}', ['as' => 'xero_commerce::setting.product.show',
                        'uses' => 'ProductController@show']);
                    Route::get('/{productId}/edit', ['as' => 'xero_commerce::setting.product.edit',
                        'uses' => 'ProductController@edit']);
                    Route::post('/{productId}/update', ['as' => 'xero_commerce::setting.product.update',
                        'uses' => 'ProductController@update']);
                    Route::post('/{productId}/remove', ['as' => 'xero_commerce::setting.product.remove',
                        'uses' => 'ProductController@remove']);

                    Route::get('/category/child', ['as' => 'xero_commerce:setting.product.category.getChild',
                        'uses' => 'ProductController@getChildCategory']);
                });

                //분류 관리
                Route::get('/category', ['as' => 'xero_commerce::setting.category.index',
                    'uses' => 'CategoryController@index',
                    'settings_menu' => 'xero_commerce.product.category']);

                //라벨 관리
                Route::group(['prefix' => 'label'], function () {
                    Route::get('/', ['as' => 'xero_commerce::setting.label.index',
                        'uses' => 'LabelController@index',
                        'settings_menu' => 'xero_commerce.product.label']);
                    Route::get('/create', ['as' => 'xero_commerce::setting.label.create',
                        'uses' => 'LabelController@create']);
                    Route::post('/store', ['as' => 'xero_commerce::setting.label.store',
                        'uses' => 'LabelController@store']);
                    Route::get('/edit/{id}', ['as' => 'xero_commerce::setting.label.edit',
                        'uses' => 'LabalController@edit']);
                    Route::post('/remove/{id}', ['as' => 'xero_commerce::setting.label.remove',
                        'uses' => 'LabelController@remove']);
                });

                //배지 관리
                Route::group(['prefix' => 'badge'], function () {
                    Route::get('/', ['as' => 'xero_commerce::setting.badge.index',
                        'uses' => 'BadgeController@index',
                        'settings_menu' => 'xero_commerce.product.badge']);
                    Route::get('/create', ['as' => 'xero_commerce::setting.badge.create',
                        'uses' => 'BadgeController@create']);
                    Route::post('/store', ['as' => 'xero_commerce::setting.badge.store',
                        'uses' => 'BadgeController@store']);
                    Route::get('/edit/{id}', ['as' => 'xero_commerce::setting.badge.edit',
                        'uses' => 'BadgeController@edit']);
                    Route::post('/remove/{id}', ['as' => 'xero_commerce::setting.badge.remove',
                        'uses' => 'BadgeController@remove']);
                });

                //주문 관리
                Route::group([
                    'prefix' => 'order',
                    'middleware' => ['admin']
                ], function () {
                    Route::get('/', [
                        'as' => 'xero_commerce::setting.order.index',
                        'uses' => 'OrderController@dash',
                        'settings_menu' => 'xero_commerce.order.index'
                    ]);
                    Route::get('/delivery', [
                        'as' => 'xero_commerce::setting.order.delivery',
                        'uses' => 'OrderController@delivery',
                        'settings_menu' => 'xero_commerce.order.delivery'
                    ]);
                    Route::post('/delivery', [
                        'as' => 'xero_commerce::process.order.delivery',
                        'uses' => 'OrderController@processDelivery'
                    ]);
                    Route::post('/delivery/complete', [
                        'as' => 'xero_commerce::complete.order.delivery',
                        'uses' => 'OrderController@completeDelivery'
                    ]);

                    Route::get('/as', [
                        'as' => 'xero_commerce::setting.order.as',
                        'uses' => 'OrderController@afterservice',
                        'settings_menu' => 'xero_commerce.order.as'
                    ]);

                    Route::get('/as/finish/{type}/{orderItem}', [
                        'as' => 'xero_commerce::setting.order.as.finish',
                        'uses' => 'OrderController@afterserviceEnd'
                    ]);

                    Route::get('/as/receive/{orderItem}', [
                        'as' => 'xero_commerce::setting.order.as.receive',
                        'uses' => 'OrderController@afterserviceReceive'
                    ]);
                });

                //쇼핑몰 설정
                Route::group(['prefix' => 'configure'], function () {
                    //쇼핑몰 환경 설정
                    Route::get('/create', ['as' => 'xero_commerce::setting.config.create',
                        'uses' => 'ShopConfigController@create',
                        'settings_menu' => 'xero_commerce.config.shopInfo']);
                    Route::post('/store', ['as' => 'xero_commerce::setting.config.store',
                        'uses' => 'ShopConfigController@store']);
                    Route::get('/setSkin', ['as' => 'xero_commerce::setting.config.skin',
                        'uses' => 'ShopConfigController@setSkin',
                        'settings_menu' => 'xero_commerce.config.setSkin']);
                    Route::get('/editTheme', ['as' => 'xero_commerce:setting.config.editTheme',
                        'uses' => 'ShopConfigController@editTheme',
                        'settings_menu' => 'xero_commerce.config.setTheme']);
                    Route::post('/updateTheme', ['as' => 'xero_commerce:setting.config.updateTheme',
                        'uses' => 'ShopConfigController@updateTheme']);

                    //입점몰 관리
                    Route::get('/shop', ['as' => 'xero_commerce::setting.config.shop.index',
                        'uses' => 'ShopController@index', 'settings_menu' => 'xero_commerce.config.storeInfo']);
                    Route::get('/shop/create', ['as' => 'xero_commerce::setting.config.shop.create',
                        'uses' => 'ShopController@create']);
                    Route::post('/shop/store', ['as' => 'xero_commerce::setting.config.shop.store',
                        'uses' => 'ShopController@store']);
                    Route::get('/shop/{shopId}', ['as' => 'xero_commerce::setting.config.shop.show',
                        'uses' => 'ShopController@show']);
                    Route::post('/shop/remove/{shopId}', ['as' => 'xero_commerce::setting.config.shop.remove',
                        'uses' => 'ShopController@remove']);
                    Route::get('/shop/edit/{shopId}', ['as' => 'xero_commerce::setting.config.shop.edit',
                        'uses' => 'ShopController@edit']);
                    Route::post('/shop/update/{shopId}', ['as' => 'xero_commerce::setting.config.shop.update',
                        'uses' => 'ShopController@update']);

                    Route::get('/user/{keyword}', [
                        'uses' => 'UserController@search',
                        'as' => 'xero_commerce::setting.search.user'
                    ]);

                    Route::get('/shop/delivery/{shop}', [
                        'as' => 'xero_commerce::setting.config.shop.delivery',
                        'uses' => 'ShopController@getDeliverys'
                    ]);

                    Route::post('/shop/delivery/add/{shop}', [
                        'as' => 'xero_commerce::setting.config.shop.add.delivery',
                        'uses' => 'ShopController@addDeliverys'
                    ]);

                    Route::post('/shop/delivery/remove/{shop}', [
                        'as' => 'xero_commerce::setting.config.shop.remove.delivery',
                        'uses' => 'ShopController@removeDeliverys'
                    ]);
                });
            });
        });

        ProductSlugService::setReserved([
            'index', 'create', 'edit', 'update', 'store', 'show', 'remove', 'slug', 'hasSlug',
            'cart', 'order', Plugin::XERO_COMMERCE_URL_PREFIX
        ]);
    }

    /**
     * @return void
     */
    public static function bindClasses()
    {
        $app = app();

        $app->singleton(ShopHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ShopHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ShopHandler::class, 'xero_commerce.shopHandler');

        $app->singleton(ProductHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductHandler::class, 'xero_commerce.productHandler');

        $app->singleton(ProductOptionItemHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductOptionItemHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductOptionItemHandler::class, 'xero_commerce.productOptionItemHandler');

        $app->singleton(ProductCategoryHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductCategoryHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(ProductCategoryHandler::class, 'xero_commerce.productCategoryHandler');

        $app->singleton(LabelHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(LabelHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(LabelHandler::class, 'xero_commerce.labelHandler');

        $app->singleton(BadgeHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(BadgeHandler::class);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(BadgeHandler::class, 'xero_commerce.badgeHandler');

        $app->singleton(OrderHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(OrderHandler::class);

            $instance = new $proxyHandler(new Order());

            return $instance;
        });
        $app->alias(OrderHandler::class, 'xero_commerce.orderHandler');


        $app->singleton(CartHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(CartHandler::class);

            $instance = new $proxyHandler(User::first());

            return $instance;
        });
        $app->alias(CartHandler::class, 'xero_commerce.cartHandler');


        $app->singleton(PaymentHandler::class, function ($app) {
            $uses = XeConfig::getOrNew('xero_pay')->get('uses');
            $useHandler = app('xe.pluginRegister')->get('xero_pay')[$uses]::$handler;
            $proxyHandler = XeInterception::proxy($useHandler);

            $instance = new $proxyHandler();

            return $instance;
        });
        $app->alias(PaymentHandler::class, 'xero_pay::paymentHandler');

        $app->when(ProductController::class)
            ->needs(SellUnit::class)
            ->give(ProductOptionItem::class);

        $app->when(ProductController::class)
            ->needs(SellType::class)
            ->give(Product::class);
    }

    /**
     * @return void
     */
    public static function setConfig()
    {
        XeConfig::set('xero_pay',['uses'=>array_keys(app('xe.pluginRegister')->get('xero_pay'))[0]]);
        \XeEditor::setInstance(Plugin::getId(), CkEditor::getId());
        \XeEditor::setConfig(Plugin::getId(), ['uploadActive' => true]);

        $category = \XeCategory::create([
            'name' => '상품 분류'
        ]);

        for ($i = 1; $i <= 3; $i++) {
            $categoryItem = self::storeCagegoryItem($category, $i);
            self::storeProduct(2, $categoryItem->id);
        }

        self::storeConfigData('categoryId', $category->id);

        self::storeDefaultMarks();
    }

    /**
     * @param string $configKey   configKey
     * @param string $configValue configValue
     *
     * @return void
     */
    public static function storeConfigData($configKey, $configValue)
    {
        $config = \XeConfig::get(Plugin::getId());
        if ($config === null) {
            \XeConfig::add(Plugin::getId(), [$configKey => $configValue]);
        } else {
            $config->set($configKey, $configValue);
            \XeConfig::modify($config);
        }
    }

    public static function storeCagegoryItem($category, $index)
    {
        $lang = ['ko' => '카테고리' . $index, 'en' => 'Category' . $index];
        $word = Plugin::XERO_COMMERCE_PREFIX . '::' . app('xe.keygen')->generate();
        $description = Plugin::XERO_COMMERCE_PREFIX . '::' . app('xe.keygen')->generate();
        foreach ($lang as $locale => $value) {
            XeLang::save($word, $locale, $value, false);
            XeLang::save($description, $locale, $value, false);
        }
        return XeCategory::createItem($category, ['word' => $word, 'description' => $description]);
    }

    /**
     * @return void
     */
    public static function storeDefaultMarks()
    {
        $labels[] = ['name' => '히트', 'eng_name' => 'hit'];
        $labels[] = ['name' => '추천', 'eng_name' => 'recommend'];
        $labels[] = ['name' => '신규', 'eng_name' => 'new'];
        $labels[] = ['name' => '인기', 'eng_name' => 'popular'];
        $labels[] = ['name' => '할인', 'eng_name' => 'sale'];

        foreach ($labels as $label) {
            $newLabel = new Label();
            $newLabel->name = $label['name'];
            $newLabel->eng_name = $label['eng_name'];

            $newLabel->save();
        }

        $badges[] = ['name' => '세일', 'eng_name' => 'sale'];
        $badges[] = ['name' => '히트', 'eng_name' => 'hit'];

        foreach ($badges as $badge) {
            $newBadge = new Badge();
            $newBadge->name = $badge['name'];
            $newBadge->eng_name = $badge['eng_name'];

            $newBadge->save();
        }
    }

    /**
     * @return void
     */
    public static function storeDefaultDeliveryCompany($name, $uri, $type)
    {
        $dc = new DeliveryCompany();
        $dc->name = $name;
        $dc->uri = $uri;
        $dc->type = $type;
        $dc->save();
    }

    public static function storeDefaultDeliveryCompanySet()
    {
        $deliery_list = [
            'cj대한통운' =>
                ['https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=',
                    DeliveryCompany::LOGIS],
            '한진택배' =>
                ['http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num=',
                    DeliveryCompany::LOGIS],
        ];
        foreach ($deliery_list as $name => $option) {
            self::storeDefaultDeliveryCompany($name, $option[0], $option[1]);
        }
    }

    /**
     * @return void
     */
    public static function storeDefaultShop()
    {
        $userId = \Auth::user()->getId();

        if (Shop::where('shop_name', Shop::BASIC_SHOP_NAME)->first() == null) {
            $args['user_id'] = $userId;
            $args['shop_type'] = Shop::TYPE_STORE;
            $args['shop_name'] = Shop::BASIC_SHOP_NAME;

            $storeHandler = new ShopHandler();
            $store = $storeHandler->store($args);

            $store->deliveryCompanys()->attach(DeliveryCompany::pluck('id'));
        }
    }

    public static function storeAgreement($type, $name)
    {
        $contents = '약관 샘플';
        $agree = new Agreement();
        $agree->type = $type;
        $agree->name = $name;
        $agree->version = '1.0.0';
        $agree->contents = $contents;
        $agree->save();
    }

    public static function storeProduct($count, $category_id)
    {
        $faker = Factory::create('ko_kr');

        for ($i = 0; $i < $count; $i++) {
            $product = new Product();
            $product->shop_id = rand(1, Shop::count());
            $product->product_code = $faker->numerify('###########');
            $product->detail_info = json_encode([
                '상품정보' => '샘플 상품',
                '비고' => '수정해서 사용'
            ]);
            $product->name = '지금부터 봄까지 입는 데일리 인기신상 ITEM' . ($i + 1);
            $product->sub_name = '간단한 상품설명';
            $product->original_price = $faker->numberBetween(1, 50) * 1000;
            $product->sell_price = $product->original_price - ($product->original_price * rand(0, 10) / 100);
            $product->discount_percentage = round(
                (($product->original_price - $product->sell_price) * 100 / $product->original_price)
            );
            $product->description = '상품설명페이지';
            $product->tax_type = rand(Product::TAX_TYPE_TAX, Product::TAX_TYPE_FREE);
            $product->state_display = Product::DISPLAY_VISIBLE;
            $product->state_deal = Product::DEAL_ON_SALE;
            $product->shop_delivery_id = Shop::find($product->shop_id)->deliveryCompanys()->first()->pivot->id;
            $product->save();

            $url= file_get_contents(Plugin::asset('assets/sample/tmp_tablist.jpg'));
            $file = XeStorage::create($url,'public/xero_commerce/product','default.jpg');
            $imageFile = XeMedia::make($file);
            XeMedia::createThumbnails($imageFile, 'widen',config('xe.media.thumbnail.dimensions'));
            $product->images()->attach($imageFile->id);

            self::storeProductOption($product->id);

            ProductSlugService::storeSlug($product, new Request());

            $newProductCategory = new ProductCategory();

            $newProductCategory->product_id = $product->id;
            $newProductCategory->category_id = $category_id;

            $newProductCategory->save();

            $labels = Label::pluck('id')->toArray();
            $labelCount = count($labels);

            for ($j = 0; $j < rand(0, $labelCount); $j++) {
                $newProductLabel = new ProductLabel();

                $newProductLabel->product_id = $product->id;
                $newProductLabel->label_id = $labels[rand(0, $labelCount - 1)];

                $newProductLabel->save();
            }
        }
    }

    public static function storeProductOption($product_id)
    {
        $faker = Factory::create('ko_kr');
        for ($i = 0; $i < rand(1, 4); $i++) {
            $op = new ProductOptionItem();
            $op->product_id = $product_id;

            if ($i == 0) {
                $op->option_type = ProductOptionItem::TYPE_DEFAULT_OPTION;
                $op->addition_price = 0;
            } else {
                $op->option_type = rand(ProductOptionItem::TYPE_OPTION_ITEM, ProductOptionItem::TYPE_ADDITION_ITEM);
                $op->addition_price = $faker->numberBetween(0, 10) * 500;
            }

            $op->name = '옵션' . ($i + 1);
            $op->stock = 10;
            $op->alert_stock = 1;
            $op->state_display = ProductOptionItem::DISPLAY_VISIBLE;
            $op->state_deal = ProductOptionItem::DEAL_ON_SALE;
            $op->save();
        }
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
                'xero_commerce' => [
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
            'xero_commerce.product' => [
                'title' => '상품관리',
                'display' => true,
                'description' => '',
                'ordering' => 10001
            ],
            'xero_commerce.product.list' => [
                'title' => '전체 상품목록',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
            'xero_commerce.product.create' => [
                'title' => '상품 등록',
                'display' => true,
                'description' => '',
                'ordering' => 100012
            ],
            'xero_commerce.product.category' => [
                'title' => '분류 관리',
                'display' => true,
                'description' => '',
                'ordering' => 100013
            ],
            'xero_commerce.product.label' => [
                'title' => '라벨 관리',
                'display' => true,
                'description' => '',
                'ordering' => 100014
            ],
            'xero_commerce.product.badge' => [
                'title' => '배지 관리',
                'display' => true,
                'description' => '',
                'ordering' => 100015
            ]
        ];
    }

    /**
     * @return array
     */
    private static function menuOrder()
    {
        return [
            'xero_commerce.order' => [
                'title' => '주문',
                'display' => true,
                'description' => '',
                'ordering' => 10002
            ],
            'xero_commerce.order.index' => [
                'title' => '전체 주문내역',
                'display' => true,
                'description' => '',
                'ordering' => 100021
            ],
            'xero_commerce.order.delivery' => [
                'title' => '주문 배송처리',
                'display' => true,
                'description' => '',
                'ordering' => 100022
            ],
            'xero_commerce.order.as' => [
                'title' => '교환/환불 처리',
                'display' => true,
                'description' => '',
                'ordering' => 100023
            ],
        ];
    }

    /**
     * @return array
     */
    private static function menuConfigure()
    {
        return [
            'xero_commerce.config' => [
                'title' => '환경설정',
                'display' => true,
                'description' => '',
                'ordering' => 10003
            ],
            'xero_commerce.config.shopInfo' => [
                'title' => '쇼핑몰 정보 등록',
                'display' => true,
                'description' => '',
                'ordering' => 100031
            ],
            'xero_commerce.config.setSkin' => [
                'title' => '스킨 설정',
                'display' => true,
                'description' => '',
                'ordering' => 100032
            ],
            'xero_commerce.config.setTheme' => [
                'title' => '테마 설정',
                'display' => true,
                'description' => '',
                'ordering' => 100033
            ],
            'xero_commerce.config.storeInfo' => [
                'title' => '입점몰 정보',
                'display' => true,
                'description' => '',
                'ordering' => 100034
            ],
        ];
    }
}
