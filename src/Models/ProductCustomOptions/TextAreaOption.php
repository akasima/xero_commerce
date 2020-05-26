<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions;

class TextAreaOption extends TextOption
{

    public static $singleTableType = 'textarea';

    public static $singleTableName = '긴 텍스트';

    public function renderInputHtml(array $attrs = [])
    {
        $result = '<textarea ';
        if(!array_has($attrs, 'name')) {
            $result .= "name=\"custom_options[{$this->id}][value]\" ";
        }
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
