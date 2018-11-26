{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<h2>장바구니</h2>
<div id="component-container">
    <cart-component
        :cart-list='{!! $cartList !!}'
        summary-url="{{ route('xero_commerce::cart.summary') }}"
        order-url="{{ route('xero_commerce::order.register') }}"
        cart-change-url="{{ route('xero_commerce::cart.change',['cart'=>'']) }}"
        cart-draw-url="{{ route('xero_commerce::cart.draw',['cart'=>'']) }}"
        cart-draw-list-url="{{ route('xero_commerce::cart.drawList') }}"
        wish-url="{{route('xero_commerce::wish.index')}}"
        wish-add-url="{{route('xero_commerce::cart.wish')}}"
    ></cart-component>
    <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
</div>
