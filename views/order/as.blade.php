{{--@deprecated since ver 1.1.4--}}
{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<h2>{{$type}}페이지</h2>
<div id="component-container">
    <order-after-service-component
        :order='{!! $order !!}'
        :item='{!! json_encode($item) !!}'
        type="{{$type}}"
        :company='{!! $company !!}'
        :pay-methods='{!! json_encode($payMethods) !!}'
        execute-url='{{route('xero_commerce::order.as.register', ['type'=>'','orderItem'=>''])}}'
        success-url='{{route('xero_commerce::order.index')}}'
        fail-url='{{route('xero_commerce::order.fail',['order'=>$order])}}'
        token="{{csrf_token()}}"></order-after-service-component>

</div>
