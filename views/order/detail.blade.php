{{--@deprecated since ver 1.1.4--}}
{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<div id="component-container">
    <order-detail-component :order='{!! $order !!}'
                            detail-url="{{route('xero_commerce::order.detail',['order'=>''])}}"
                            cancel-url="{{route('xero_commerce::order.cancel',['order'=>''])}}"></order-detail-component>
</div>
