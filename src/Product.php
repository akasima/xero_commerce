<?php
/**
 * Created by PhpStorm.
 * User: yeong-ung-gim
 * Date: 2018. 9. 13.
 * Time: 오후 3:12
 */

namespace Xpressengine\Plugins\XeroStore;


interface Product
{
    public function getDefaultOption();

    public function options();

    public function getName();

    public function getInfo();
}