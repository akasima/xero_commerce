{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<h2>장바구니</h2>
<div id="component-container">
    <cart-component
        :cart-items='{!! $cartItems !!}'
        summary-url="{{ route('xero_commerce::cartitem.summary') }}"
        order-url="{{ route('xero_commerce::order.register') }}"
        cart-change-url="{{ route('xero_commerce::cartitem.change', ['']) }}"
        cart-delete-url="{{ route('xero_commerce::cartitem.delete', ['']) }}"
        cart-delete-list-url="{{ route('xero_commerce::cartitem.deleteList') }}"
        wish-url="{{route('xero_commerce::wish.index')}}"
        wish-add-url="{{route('xero_commerce::cartitem.wish')}}"
    ></cart-component>
    <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
</div>
