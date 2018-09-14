<?php

namespace Xpressengine\Plugins\XeroStore\Interfaces;

interface Product
{
    public function getDefaultOption();

    public function options();

    public function getName();

    public function getInfo();
}
