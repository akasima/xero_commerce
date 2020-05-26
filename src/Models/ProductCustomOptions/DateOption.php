<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions;

class DateOption extends TextOption
{

    public static $singleTableType = 'date';

    public static $singleTableName = '날짜';

    protected static $singleTableSubclasses = [MonthOption::class];

    public function renderInputHtml(array $attrs = [])
    {
        $class = array_get($attrs, 'class', '');
        $attrs['class'] = $class.' date-picker';

        \XeFrontend::js('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js')->load();
        \XeFrontend::js('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ko.min.js')->load();
        \XeFrontend::css('//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css')->load();
        \XeFrontend::html('product.customOption.date')->content("
        <script>
        $(function() {
            $('.date-picker').datepicker({
                language: 'ko',
                autoclose: true,
                format: 'yyyy-mm-dd'
            })
            .on('changeDate', function(e) {
                // Vue 호환성
                $(e.target)[0].dispatchEvent(new Event('input'));
                $(e.target)[0].dispatchEvent(new Event('change'));
            });
        })
        </script>
        ")->load();

        return parent::renderInputHtml($attrs);
    }

}
