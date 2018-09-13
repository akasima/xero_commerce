<?php
/**
 * Created by PhpStorm.
 * User: yeong-ung-gim
 * Date: 2018. 9. 13.
 * Time: 오후 3:12
 */

namespace Xpressengine\Plugins\XeroStore;


interface Option
{
    public function product();

    public function getPrice();

    public function getInfo();
}