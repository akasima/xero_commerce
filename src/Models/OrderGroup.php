<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;


use Xpressengine\Database\Eloquent\DynamicModel;

abstract class OrderGroup extends DynamicModel
{
    abstract function getCount();
}