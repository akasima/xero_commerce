{{-- script --}}
{{ app('xe.frontend')->js([
    'assets/vendor/bootstrap/js/bootstrap.min.js'
])->load() }}

{{-- stylesheet --}}
{{ app('xe.frontend')->css([
    'assets/vendor/bootstrap/css/bootstrap.min.css',
    $theme::asset('css/user/layout.css')
])->load() }}

{{
 app('xe.frontend')->meta('my.viewport')->name('viewport')
->content('width=device-width, height=device-height, initial-scale=1, minimum-scale=1.0, maximum-scale=1.0')->load()
}}

@php
    $shopConfig = XeConfig::getOrNew(\Xpressengine\Plugins\XeroCommerce\Plugin::getId());
@endphp

<div class="xe-shop">

    <!-- header -->
    <header class="header">
        <div class="inner-header">

            <!-- area-option -->
            <div class="area-option">
                <ul class="list-option">
                    @if(auth()->check())
                        @if(auth()->user()->rating == \Xpressengine\User\Rating::MANAGER || auth()->user()->rating == \Xpressengine\User\Rating::SUPER)
                            <li class="item-option"><a href="{{ route('xero_commerce::setting.order.index') }}">관리자페이지</a></li>
                        @endif
                        <li class="item-option"><a class="link-option" href="{{ route('logout') }}">로그아웃</a></li>
                        <li class="item-option"><a class="link-option" href="{{route('xero_commerce::order.index') }}">마이페이지</a></li>
                        <li class="item-option"><a class="link-option" href="{{route('xero_commerce::cart.index')}}">장바구니</a></li>
                        <li class="item-option"><a class="link-option" href="{{route('xero_commerce::order.list')}}">주문조회</a></li>
                    @else
                        <li class="item-option"><a class="link-option" href="{{ route('login') }}">로그인</a></li>
                        <li class="item-option"><a class="link-option" href="{{route('auth.register') }}">회원가입</a></li>
                    @endif
                </ul>
            </div>
            <!-- // area-option -->

            <!-- area-gnb -->
            <div class="area-gnb">
                <h1 class="logo">
                    @if($shopConfig['logo_id'])
                        <a href="/leaflet" class="img-logo">
                            <img id="logoPreview" name="logo" src="{{ app('xero_commerce.imageHandler')->getImageUrlByFileId($shopConfig['logo_id']) }}">
                        </a>
                    @else
                        <a href="/shopping" class="text-logo">
                            {{$shopConfig['companyName']}}
                        </a>
                    @endif
                </h1>

                <nav>
                    <ul class="list-gnb">
                        @foreach(menu_list($config->get('gnb')) as $menu)
                            <li class="item-gnb @if($menu['selected']) active @endif"><a href="{{url($menu['url'])}}" class="link-gnb" target="">{{ $menu['link'] }}</a></li>
                        @endforeach
                    </ul>
                </nav>

                <div class="area-search">
                    <form method="get" action="{{ url()->to(\Xpressengine\Plugins\XeroCommerce\Plugin::XERO_COMMERCE_MAIN_PAGE_URL) }}">
                        <input type="text" class="input-text" name="product_name">
                        <button type="button" class="btn-search reset-button"><i class="xi-search"></i><span class="xe-sr-only">검색</span></button>
                    </form>
                </div>

            </div>
            <!-- // area-gnb -->

        </div>

        <!-- area-lnb -->
        <div class="area-lnb">

            <div class="inner-header">
                <button type="button" class="btn-menu reset-button" onclick="toggleMenu()"><i class="xi-bars"></i><span class="xe-sr-only">전체 카테고리 열기</span></button>
                <ul class="list-lnb">
                    @foreach(menu_list($config->get('gnb_sub')) as $menu)
                        <li class="item-lnb"><a href="{{url($menu['url']) }}"  class="link-lnb @if($menu['selected']) active @endif" target="">{{ $menu['link'] }}</a></li>
                    @endforeach
                </ul>

                <div class="area-category">
                    <div class="inner-header">
                        <ul class="list-category">
                            <!-- [D] 열림시 open 클래스 추가 -->
                            @foreach(menu_list($config->get('gnb')) as $menu)
                                <li @if($menu['selected']) class="open item-category" @endif onclick="toggleSubMenu(this,'{{url($menu['url']) }}')">
                                    <h4 class="xe-shop-category-title"><a href="#" class="title-depth2">{{ $menu['link'] }}</a><i @if($menu['selected']) class="xi-angle-up-thin" @else class="xi-angle-down-thin" @endif></i></h4>
                                    <ul>
                                        @foreach($menu['children'] as $submenu)
                                            <li class="item-depth03"><a href="{{url($submenu['url'])}}">{{ $submenu['link'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <!-- // area-lnb -->

    </header>
    <!-- // header -->


    <main class="xero-layout-type-{{ $config->get('layout_type') }}">
        <div id="sub-container">
            {!! $content !!}
        </div>
    </main>

    <footer class="footer">

        <div class="inner-footer">
            <dl class="company">
                @if(!is_null($shopConfig['companyName']))
                    <dt class="item-name">{{$shopConfig['companyName']}}</dt>
                @endif
                @if(!is_null($shopConfig['ceoName']))
                    <dd class="item-company">대표 : {{$shopConfig['ceoName']}}</dd>
                @endif
                @if(!is_null($shopConfig['address']))
                    <dd class="item-company">@if(!is_null($shopConfig['zipCode']))({{$shopConfig['zipCode']}})@endif {{$shopConfig['address']}}</dd>
                @endif
                @if(!is_null($shopConfig['companyNumber']))
                    <dd class="item-company">사업자등록번호 : {{$shopConfig['companyNumber']}} <a href="#" class="link-info">사업자정보확인 <i class="xi-angle-right-thin"></i></a></dd>
                @endif
                @if(!is_null($shopConfig['communicationMarketingNumber']))
                    <dd class="item-company">통신판매업신고번호 : {{$shopConfig['communicationMarketingNumber']}}</dd>
                @endif
                @if(!is_null($shopConfig['telNumber']))
                    <dd class="item-company">대표전화 : {{$shopConfig['telNumber']}}</dd>
                @endif
                @if(!is_null($shopConfig['email']))
                    <dd class="item-company">이메일 :  <a href="{{$shopConfig['email']}}" class="link-email">{{$shopConfig['email']}}</a></dd>
                @endif
            </dl>
            <small>Copyright © 2016 (주)엠엠푸드 All rights reserved.   MADE BY XE</small>
        </div>

    </footer>

</div>

<script>
    function toggleMenu () {
        toggleClass(".xe-shop-category", "active")
    }

    function toggleSubMenu (target, url) {
        var was_ul_display = $(target).find("ul").css("display")==='block'

        toggleClass(target, "open")
        var icon = $(target).find("h4 i");
        toggleClass(icon, "xi-angle-down-thin");
        toggleClass(icon, "xi-angle-up-thin");

        var is_ul_dislay = $(target).find("ul").css("display")==='block'

        if (was_ul_display && is_ul_dislay) {
            location.href = url
        }
    }

    function toggleClass (target, className) {
        if($(target).hasClass(className)){
            $(target).removeClass(className);
        }else{
            $(target).addClass(className);
        }
    }
</script>

<script>
    function toggleMenu () {
        toggleClass(".area-category", "active");
        toggleClass(".btn-menu", "active")
    }
</script>
