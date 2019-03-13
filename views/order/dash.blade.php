{{--@deprecated since ver 1.1.4--}}
{{ XeFrontend::js(asset(\Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<div id="component-container">

    <dash-component
        :dashboard='{!! $dashboard !!}'
        :user='{!! \Illuminate\Support\Facades\Auth::user() !!}'
        :user-info='{!! \Xpressengine\Plugins\XeroCommerce\Models\UserInfo::by(\Illuminate\Support\Facades\Auth::id()) !!}'
        list-url="{{route('xero_commerce::order.list')}}"
        wish-url="{{route('xero_commerce::wish.index')}}"></dash-component>

</div>
