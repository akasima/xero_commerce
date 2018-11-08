{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin::asset('js/index.js')))->appendTo('body')->load() }}
<product-detail-component
:product='{!! json_encode($product->getJsonFormat()) !!}'
cart-url="{{route('xero_commerce::product.cart', ['product'=> ''])}}"
order-url="{{route('xero_commerce::order.register')}}"
cart-page-url="{{route('xero_commerce::cart.index')}}"
wish-url="{{route('xero_commerce::product.wish.toggle',['product'=>$product])}}"
wish-list-url="{{route('xero_commerce::wish.index')}}"
:category = "{{json_encode($category)}}"></product-detail-component>
<input type="hidden" id="csrf_token" value="{{csrf_token()}}">
