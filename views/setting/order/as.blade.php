{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<div id="component-container">
    <after-service-component
    :list="{{json_encode($list)}}"
    finish-url="{{route('xero_commerce::setting.order.as.finish',['type'=>'','orderItem'=>''])}}"
    receive-url="{{route('xero_commerce::setting.order.as.receive',['orderItem'=>''])}}"></after-service-component>
</div>
