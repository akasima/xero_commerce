{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin::asset('js/index.js')))->appendTo('body')->load() }}
<order-list-component
:list='{!! $list !!}'
:paginate='{!! json_encode($paginate) !!}'
load-url="{{route('xero_commerce::order.page', ['page'=>''])}}"
:status-list='{!! json_encode(\Xpressengine\Plugins\XeroCommerce\Models\Order::STATUS) !!}'
token="{{csrf_token()}}"
:default='{!! json_encode($default) !!}'></order-list-component>