{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<div id="product-container">
    <delivery-component
    :order-items='{!! $orderItems !!}'
    token="{{csrf_token()}}"
    ></delivery-component>
</div>