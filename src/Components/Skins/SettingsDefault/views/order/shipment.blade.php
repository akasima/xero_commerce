{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}

<div id="component-container">
    <shipment-component :order-items='{!! $orderItems !!}' token="{{csrf_token()}}"></shipment-component>
</div>
