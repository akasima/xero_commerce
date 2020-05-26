<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions;

class MonthOption extends DateOption
{

    public static $singleTableType = 'month';

    public static $singleTableName = '월 선택';

    public function renderInputHtml(array $attrs = [])
    {
        $html = parent::renderInputHtml($attrs);

        \XeFrontend::html('product.customOption.month')->content("
        <script>
        $(function() {
            $('.month-picker').datepicker({
                language: 'ko',
                startView: 'months',
                minViewMode: 'months',
                autoclose: true,
                format: 'yyyy-mm-01'
            })
            .on('changeDate', function(e) {
                // Vue 호환성
                $(e.target)[0].dispatchEvent(new Event('input'));
                $(e.target)[0].dispatchEvent(new Event('change'));
            });
        })
        </script>
        ")->load();

        return substr_replace($html, 'month-picker', strpos($html, 'date-picker'), strlen('date-picker'));
    }
}
