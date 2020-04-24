<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions;

use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOption;

class SelectOption extends ProductCustomOption
{

    public static $singleTableType = 'select';

    public static $singleTableName = '목록선택';

    public function renderHtml(array $attrs)
    {
        $result = '<select ';
        foreach ($attrs as $key => $value) {
            $result .= "$key=\"$value\" ";
        }
        if($this->is_required) {
            $result .= 'required ';
        }
        $result .= '>';
        if(!empty($this->settings['options'])) {
            foreach ($this->settings['options'] as $name => $value) {
                $result .= "<option value=\"$value\">$name</option>";
            }
        }
        $result .= '</select>';
        return $result;
    }

}
