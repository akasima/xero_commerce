<?php

namespace Xpressengine\Plugins\XeroStore\Controllers\Settings;

use XeConfig;
use XePresenter;
use Xpressengine\Category\Models\Category;
use Xpressengine\Plugins\XeroStore\Plugin;

class CategoryController
{
    public function index()
    {
        $config = XeConfig::get(Plugin::getId());

        $category = Category::find($config->get('categoryId'));

        if ($category === null) {
            throw new \Exception;
        }

        // 카테고리 관리자 view 사용
        XePresenter::setSettingsSkinTargetId('');
        return XePresenter::make('category.show', compact('category'));
    }
}
