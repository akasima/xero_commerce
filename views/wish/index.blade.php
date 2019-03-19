{{--@deprecated since ver 1.1.4--}}
{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<div id="component-container">
    <wish-component
    :list="{{json_encode($list)}}"
    cart-url="{{route('xero_commerce::product.cart', ['product'=> ''])}}"
    remove-url="{{route('xero_commerce::wish.remove')}}"
    cart-page-url="{{route('xero_commerce::cart.index')}}"></wish-component>
</div>
<input type="hidden" id="csrf_token" value="{{csrf_token()}}">
