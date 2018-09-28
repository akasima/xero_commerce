{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin::asset('js/index.js')))->appendTo('body')->load() }}
<div class="title">{{ $title }}</div>
<dash-component :dashboard='{!! $dashboard !!}' :user='{!! \Illuminate\Support\Facades\Auth::user() !!}'></dash-component>