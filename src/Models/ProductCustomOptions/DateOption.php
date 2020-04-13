<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions;

use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOption;

class DateOption extends ProductCustomOption
{

    public static $singleTableType = 'date';

    public static $singleTableName = '날짜';

    public function renderHtml(array $attrs)
    {
        $result = '<input type="date" ';
        foreach ($attrs as $key => $value) {
            $result .= "$key=\"$value\" ";
        }
        if($this->is_required) {
            $result .= 'required ';
        }
        $result .= '/>';
        return $result;
    }

}
