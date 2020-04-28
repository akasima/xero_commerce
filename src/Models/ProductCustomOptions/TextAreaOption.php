<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions;

use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOption;

class TextAreaOption extends ProductCustomOption
{

    public static $singleTableType = 'textarea';

    public static $singleTableName = '긴 텍스트';

    public function renderHtml(array $attrs)
    {
        $result = '<textarea ';
        foreach ($attrs as $key => $value) {
            $result .= "$key=\"$value\" ";
        }
        if($this->is_required) {
            $result .= 'required ';
        }
        $result .= '></textarea>';
        return $result;
    }
}
