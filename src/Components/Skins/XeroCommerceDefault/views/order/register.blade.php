{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin::asset('js/index.js')))->appendTo('body')->load() }}
<h2>주문 및 결제</h2>
<order-register-component
        dash-url="{{instance_route('xero_commerce::order.index')}}"
        success-url="{{instance_route('xero_commerce::order.success',['order' => $order->id])}}"
        fail-url="{{instance_route('xero_commerce::order.fail',['order' => $order->id])}}"
        :order-item-list='{!! $orderItems !!}'
        :order-summary='{!! json_encode($summary)  !!}'
        :user='{!! \Illuminate\Support\Facades\Auth::user() !!}'
        order_id="{{$order->id}}"></order-register-component>
<input type="hidden" id="csrf_token" value="{{csrf_token()}}">