<?php
/**
 * Created by PhpStorm.
 * User: yeong-ung-gim
 * Date: 2018. 9. 13.
 * Time: 오전 10:15
 */

namespace Xpressengine\Plugins\XeroStore;


class Goods
{
    public $option;
    public $count;

    public function __construct(Option $option, $count = 1)
    {
        $this->option = $option;
        $this->count = $count;
    }

    public function getProduct()
    {
        return $this->option->product();
    }

    public function getOption()
    {
        return $this->option;
    }

    public function getInfo()
    {
        return $this->getProduct()->description;
    }

    public function setCount($count)
    {
        return $this->count = $count;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function getEachPrice()
    {
        return $this->option->getPrice();
    }

    public function getPrice()
    {
        return $this->getcount() * $this->getEachPrice();
    }

}