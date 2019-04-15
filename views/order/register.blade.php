{{--@deprecated since ver 1.1.4--}}
{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<h2>주문 및 결제</h2>
<div id="component-container">
    <order-register-component
        :agreements='{{json_encode($agreements)}}'
        dash-url="{{route('xero_commerce::order.complete',['order' => $order->id])}}"
        success-url="{{route('xero_commerce::order.success',['order' => $order->id])}}"
        fail-url="{{route('xero_commerce::order.fail',['order' => $order->id])}}"
        :order-item-list='{!! $orderItems !!}'
        :order-summary='{!! json_encode($summary)  !!}'
        :user='{!! \Illuminate\Support\Facades\Auth::user()->makeVisible('email') !!}'
        :user-info='{!! \Xpressengine\Plugins\XeroCommerce\Models\UserInfo::by(\Illuminate\Support\Facades\Auth::id())->load('userDelivery') !!}'
        order_id="{{$order->id}}"
        :pay-methods='{{ json_encode($payMethods)  }} '
        :pay-target="{{json_encode($order->getPayInfo())}}"
        agree-url="{{route('xero_commerce::agreement.order.save',['order'=>$order->id])}}"
        denied-url="{{route('xero_commerce::agreement.order.cancel',['order'=>$order->id])}}"
    ></order-register-component>
    <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
</div>
