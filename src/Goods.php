<?php
/**
 * Created by PhpStorm.
 * User: yeong-ung-gim
 * Date: 2018. 9. 13.
 * Time: 오전 10:15
 */

namespace Xpressengine\Plugins\XeroStore;


interface Goods
{
    public function getProduct();

    public function getOption();

    public function getInfo();

    public function setCount();

    public function getcount();

}