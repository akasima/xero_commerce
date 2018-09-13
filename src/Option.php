<?php

namespace Xpressengine\Plugins\XeroStore;

interface Option
{
    public function product();

    public function getPrice();

    public function getInfo();
}
