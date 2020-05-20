<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions;

use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOption;

class TextOption extends ProductCustomOption
{

    public static $singleTableType = 'text';

    public static $singleTableName = '텍스트';

    protected static $singleTableSubclasses = [DateOption::class, TextAreaOption::class];

    public function renderValueInput(array $attrs)
    {
        $result = '<input type="text" ';
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
