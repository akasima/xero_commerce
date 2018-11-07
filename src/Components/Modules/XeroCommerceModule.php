<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Modules;

use Route;
use XeConfig;
use View;
use Xpressengine\Category\Models\Category;
use Xpressengine\Menu\AbstractModule;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class XeroCommerceModule extends AbstractModule
{
    protected $moduleListConfigKey;

    public function __construct()
    {
        $this->moduleListConfigKey = sprintf('%s.%s', Plugin::getId(), 'mainModuleList');
    }

    public static function boot()
    {
        Route::instance(XeroCommerceModule::getId(), function () {
            Route::match(['get','post'],'/', ['as' => 'xero_commerce::product.index', 'uses' => 'ProductController@index']);
        }, ['namespace' => 'Xpressengine\\Plugins\\XeroCommerce\\Controllers']);
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

        $this->storeModuleListConfig($instanceId);

        app('xe.widgetbox')->create(['id' => Plugin::XERO_COMMERCE_PREFIX . '-' . $instanceId . '-top',
            'title' => 'main module widget', 'content' => '']);

        app('xe.widgetbox')->create(['id' => Plugin::XERO_COMMERCE_PREFIX . '-' . $instanceId . '-bottom',
            'title' => 'main module widget', 'content' => '']);

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

        $this->removeModuleListConfig($instanceId);

        app('xe.widgetbox')->delete(Plugin::XERO_COMMERCE_PREFIX . '-' .  $instanceId . '-top');
        app('xe.widgetbox')->delete(Plugin::XERO_COMMERCE_PREFIX . '-' .  $instanceId . '-bottom');

        return '';
    }

    public function getTypeItem($id)
    {
        return '';
    }

    private function storeModuleListConfig($instanceId)
    {
        $moduleListConfig = XeConfig::get($this->moduleListConfigKey);
        if ($moduleListConfig == null) {
            $moduleListConfig = XeConfig::add($this->moduleListConfigKey, []);
        }

        $moduleList = $moduleListConfig->get('moduleList', []);

        array_push($moduleList, $instanceId);

        $moduleListConfig->set('moduleList', $moduleList);

        XeConfig::modify($moduleListConfig);
    }

    private function removeModuleListConfig($instanceId)
    {
        $moduleListConfig = XeConfig::get($this->moduleListConfigKey);
        if ($moduleListConfig == null) {
            return '';
        }

        $moduleList = $moduleListConfig->get('moduleList', []);

        if (($key = array_search($instanceId, $moduleList)) !== false) {
            unset($moduleList[$key]);
        }

        $moduleListConfig->set('moduleList', $moduleList);

        XeConfig::modify($moduleListConfig);
    }
}
