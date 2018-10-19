<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Modules;

use Illuminate\Support\Facades\Log;
use Route;
use XeConfig;
use View;
use Xpressengine\Category\Models\Category;
use Xpressengine\Menu\AbstractModule;
use Xpressengine\Plugins\XeroCommerce\Middleware\AgreementMiddleware;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class XeroCommerceModule extends AbstractModule
{
    public static function boot()
    {
        Route::group([
            'prefix' => Plugin::XeroCommercePrefix,
            'namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers',
            'middleware' => ['web']
        ], function () {



            Route::post('/product/cart/{product}', [
                'uses' => 'ProductController@cartAdd',
                'as' => 'xero_commerce::product.cart'
            ]);

            Route::get('/{strSlug}', ['as' => 'xero_commerce::product.show', 'uses' => 'ProductController@show']);
        });

        Route::instance(XeroCommerceModule::getId(), function () {
            Route::get('/', ['as' => 'xero_commerce::product.index', 'uses' => 'ProductController@index']);
        }, ['namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers']);
        Log::info('module');
    }

    public function createMenuForm()
    {
        $config = XeConfig::get(Plugin::getId());
        $categoryItems = Category::find($config->get('categoryId'))->getProgenitors();
        $labels = Label::get();
        $plugin = Plugin::class;

        return View::make('xero_commerce::views/setting/module/create', [
            'categoryItems' => $categoryItems,
            'plugin' => $plugin,
            'labels' => $labels
        ])->render();
    }

    public function storeMenu($instanceId, $menuTypeParams, $itemParams)
    {
        $configValue['categoryItemId'] = $menuTypeParams['categoryItemId'];
        $configValue['categoryItemDepth'] = $menuTypeParams['categoryItemDepth'];

        //menuTypeParams['label'][0]은 null이 들어있음
        if (isset($menuTypeParams['labels'][1])) {
            array_shift($menuTypeParams['labels']);
            $configValue['labels'] = $menuTypeParams['labels'];
        }

        XeConfig::add(sprintf('%s.%s', Plugin::getId(), $instanceId), $configValue);

        return '';
    }

    public function editMenuForm($instanceId)
    {
        //TODO 수정 기존 데이터 전달 필요
        $config = XeConfig::get(Plugin::getId());
        $categoryItems = Category::find($config->get('categoryId'))->getProgenitors();

        $labels = Label::get();
        $moduleLabels = XeConfig::get(sprintf('%s.%s', Plugin::getId(), $instanceId))['labels'];
        if ($moduleLabels === null) {
            $moduleLabels = [];
        }

        $plugin = Plugin::class;

        return View::make('xero_commerce::views/setting/module/edit', [
            'categoryItems' => $categoryItems,
            'plugin' => $plugin,
            'labels' => $labels,
            'moduleLabels' => $moduleLabels
        ])->render();
    }

    public function updateMenu($instanceId, $menuTypeParams, $itemParams)
    {
        $config = XeConfig::get(sprintf('%s.%s', Plugin::getId(), $instanceId));

        $config['categoryItemId'] = $menuTypeParams['categoryItemId'];
        $config['categoryItemDepth'] = $menuTypeParams['categoryItemDepth'];

        if (isset($menuTypeParams['labels'][1])) {
            array_shift($menuTypeParams['labels']);
            $config['labels'] = $menuTypeParams['labels'];
        }

        XeConfig::modify($config);

        return '';
    }

    public function summary($instanceId)
    {
        return '';
    }

    public function deleteMenu($instanceId)
    {
        XeConfig::remove(XeConfig::get(sprintf('%s.%s', Plugin::getId(), $instanceId)));

        return '';
    }

    public function getTypeItem($id)
    {
        return '';
    }
}
