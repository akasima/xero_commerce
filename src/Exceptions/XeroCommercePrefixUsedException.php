<?php

namespace Xpressengine\Plugins\XeroCommerce\Exceptions;

use Xpressengine\Support\Exceptions\XpressengineException;

class XeroCommercePrefixUsedException extends XpressengineException
{
    protected $message = 'shopping url이 이미 사용중입니다.';
}
