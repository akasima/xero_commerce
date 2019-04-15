<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 12/12/2018
 * Time: 6:39 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Services;


use Xpressengine\Plugins\XeroCommerce\Handlers\CommunicationHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;

class CommunicationManageService
{
    /**
     * @var CommunicationHandler
     */
    private $handler;

    public function __construct()
    {
        $this->handler=app('xero_commerce.communicationHandler');
    }

    public function getList($type)
    {
        $model = $this->handler->getTypeModel($type);
        $list = $this->handler->getList($model);
        return $list;
    }

    public function getItem($type, $id)
    {
        $model = $this->handler->getTypeModel($type);
        $item = $this->handler->getItem($model, $id);
        return $item;
    }

    private function getTarget($item)
    {
        return $item->target;
    }

    public function getTargetInfo($item)
    {
        $target= $this->getTarget($item);
        $data = $this->handler->getTargetInfo($target);
        return $data;
    }
}
