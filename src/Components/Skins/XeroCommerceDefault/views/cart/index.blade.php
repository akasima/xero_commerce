{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin::asset('js/index.js')))->appendTo('body')->load() }}
<h2>장바구니</h2>
<cart-component load-url="{{route('xero_commerce::cart.list')}}" summary-url="{{route('xero_commerce::cart.summary')}}"></cart-component>
<form action="{{route('xero_commerce::order.register')}}" method="post">
    {{csrf_field()}}
</form>