{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<div id="component-container">
    <order-detail-component :order='{!! $order !!}'
                            detail-url="{{route('xero_commerce::order.detail',['order'=>''])}}"></order-detail-component>
</div>
