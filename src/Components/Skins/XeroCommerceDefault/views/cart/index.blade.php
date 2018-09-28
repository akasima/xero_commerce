{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin::asset('js/index.js')))->appendTo('body')->load() }}
<h2>장바구니</h2>
<cart-component :cart-list='{!! $cartList !!}' summary-url="{{instance_route('xero_commerce::cart.summary')}}" order-url="{{instance_route('xero_commerce::order.register')}}"></cart-component>
<input type="hidden" id="csrf_token" value="{{csrf_token()}}">