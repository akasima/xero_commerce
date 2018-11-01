{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<div id="component-container">
    <order-list-component
        :list='{!! $list !!}'
        :paginate='{!! json_encode($paginate) !!}'
        load-url="{{route('xero_commerce::order.page', ['page'=>''])}}"
        as-url="{{route('xero_commerce::order.as',['as'=> '', 'order'=>'', 'item'=>''])}}"
        cancel-url="{{route('xero_commerce::order.cancel',['order'=>''])}}"
        :status-list='{!! json_encode(\Xpressengine\Plugins\XeroCommerce\Models\Order::STATUS) !!}'
        detail-url="{{route('xero_commerce::order.detail',['order'=>''])}}"
        token="{{csrf_token()}}"
        :default='{!! json_encode($default) !!}'></order-list-component>

</div>
