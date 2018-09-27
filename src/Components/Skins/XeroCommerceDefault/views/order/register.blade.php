{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin::asset('js/index.js')))->appendTo('body')->load() }}
<h2>주문 및 결제</h2>
<order-register-component :order-item-list='{!! $orderItems !!}' :order-summary='{!! json_encode($summary)  !!}' :user='{!! \Illuminate\Support\Facades\Auth::user() !!}' order_id="{{$order->id}}"></order-register-component>
<input type="hidden" id="csrf_token" value="{{csrf_token()}}">