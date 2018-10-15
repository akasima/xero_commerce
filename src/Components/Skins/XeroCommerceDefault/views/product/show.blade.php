{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin::asset('js/index.js')))->appendTo('body')->load() }}
<product-detail-component
:product='{!! json_encode($product->getJsonFormat()) !!}'></product-detail-component>