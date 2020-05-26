<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions;

use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOption;

class TextOption extends ProductCustomOption
{

    public static $singleTableType = 'text';

    public static $singleTableName = '텍스트';

    protected static $singleTableSubclasses = [DateOption::class, TextAreaOption::class];

    public function renderInputHtml(array $attrs = [])
    {
        $result = '<input type="text" ';
        if(!array_has($attrs, 'name')) {
            $result .= "name=\"custom_options[{$this->id}][value]\" ";
        }
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
