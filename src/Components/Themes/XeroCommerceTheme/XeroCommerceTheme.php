<?php

namespace Xpressengine\Plugins\XeroCommerce\Components\Themes\XeroCommerceTheme;

use Xpressengine\Plugins\Board\Models\Board;
use Xpressengine\Plugins\Board\UrlHandler;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Theme\GenericTheme;

class XeroCommerceTheme extends GenericTheme
{
    protected static $path = 'xero_commerce/src/Components/Themes/XeroCommerceTheme';

    public function render()
    {
        $urlHandler = new UrlHandler();

        $config = \XeConfig::get(Plugin::getId());
        $noticeBoardId = $config->get('noticeBoardId');

        $model = Board::division($noticeBoardId);
        /** @var \Xpressengine\Database\DynamicQuery $query */
        $query = $model->where('instance_id', $noticeBoardId);

        $query = $query->orderBy(Board::CREATED_AT, 'desc')->orderBy('head', 'desc');

        $notices = $query->take(1)->get();

        self::$handler->getViewFactory()->share('notices', $notices);
        self::$handler->getViewFactory()->share('urlHandler', $urlHandler);

        return parent::render();
    }
}
