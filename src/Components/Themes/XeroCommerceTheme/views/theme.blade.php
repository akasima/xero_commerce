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
                <h1 class="logo"><a href="/shopping">
                    @if($shopConfig['logo_id'])
                            <img id="logoPreview" style="display:inline" name="logo" src="{{ app('xero_commerce.imageHandler')->getImageUrlByFileId($shopConfig['logo_id']) }}">
                    @else
                        {{$shopConfig['companyName']}}
                    @endif
                    </a></h1>
                
                <nav>
                    <ul class="list-gnb">
                        @php
                        $mainTheme = 'theme.settings.'.\App\Facades\XeConfig::get('theme.setting')->get('siteTheme.desktop');
                        @endphp
                        @foreach(menu_list(\App\Facades\XeConfig::get($mainTheme)->get('mainMenu')) as $menu)
                            <li class="item-gnb @if($menu['selected']) active @endif"><a href="{{url($menu['url'])}}" class="link-gnb" target="">{{ $menu['link'] }}</a></li>
                        @endforeach
                    </ul>
                </nav>

                <div class="area-search">
                    <form method="get" action="{{ url()->to(\Xpressengine\Plugins\XeroCommerce\Plugin::XERO_COMMERCE_URL_PREFIX) }}">
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
                    @foreach(menu_list($config->get('gnb')) as $menu)
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



    <!-- 아래부터 위에 개발 붙여주고 지어줘요오오오 시작 -->
    <h1 class="xe-sr-only">xe 쇼핑</h1>
    <!-- 기본 ui
      scss/user/_skin.scss
      include/default_header.html
      include/default_logo.html
      include/default_menu.html
      include/default_search.html
      include/default_slider.html
    -->
    <section class="header" style="display:none;">
        <!-- [D] width를 제어하기 위해 사용하는 클래스입니다. 100% 넓이를 사용하는 위젯은 사용할 필요가 없습니다. -->
        <div class="container">
            <h2 class="xe-sr-only">상단 유틸</h2>
            <article class="xe-shop-notice xe-hidden-xs xe-hidden-sm">
                {{--<h3>notice</h3>--}}
                {{--<div class="notice-view">--}}
                {{--<ul>--}}
                {{--@foreach ($notices as $notice)--}}
                {{--<li><a href="{{ $urlHandler->getShow($notice) }}">{{ $notice->title }} </a></li>--}}
                {{--@endforeach--}}
                {{--</ul>--}}
                {{--</div>--}}
                <div>
                    <img src="{{ $config->get('logo_image.path') }}" alt="">
                </div>
            </article>
            <article class="xe-shop-utilmenu xe-hidden-xs xe-hidden-sm">
                <h2 class="xe-sr-only">관련 링크</h2>
                <ul class="xe-shop-utilmenu-list">
                    @if(auth()->check())
                        @if(auth()->user()->rating == \Xpressengine\User\Rating::MANAGER || auth()->user()->rating == \Xpressengine\User\Rating::SUPER)
                            <li><a style="color:red" href="{{ route('xero_commerce::setting.order.index') }}">관리자페이지</a></li>
                        @endif
                        <li><a href="{{ route('logout') }}">로그아웃</a></li>
                        <li><a href="{{route('xero_commerce::order.index') }}">마이페이지</a></li>
                        <li><a href="{{route('xero_commerce::cart.index')}}">장바구니</a></li>
                        <li><a href="{{route('xero_commerce::order.list')}}">주문조회</a></li>
                    @else
                        <li><a href="{{ route('login') }}">로그인</a></li>
                        <li><a href="{{route('auth.register') }}">회원가입</a></li>
                    @endif

                </ul>
                <h2 class="xe-sr-only">검색</h2>
                <form method="get" action="{{ url()->to(\Xpressengine\Plugins\XeroCommerce\Plugin::XERO_COMMERCE_URL_PREFIX) }}">
                    <div class="xe-shop-search-input">
                        <input type="text" class="xe-form-control" placeholder="" name="product_name" value="{{ Request::get('product_name') }}">
                        <button type="button"><i class="xi-search"></i><span class="xe-sr-only">검색</span></button>
                    </div>
                </form>
            </article>
        </div>
    </section>




    <section class="logo" style="display:none;">
        <div class="container">
            <h2 class="xe-shop-logo">
                <a href="{{ url()->to(\Xpressengine\Plugins\XeroCommerce\Plugin::XERO_COMMERCE_URL_PREFIX) }}">
                    <img alt="쇼핑몰 로고"
                         @if (app('xero_commerce.imageHandler')->getImageUrlByFileId($shopConfig['logo_id']) != '')
                         src="{{ app('xero_commerce.imageHandler')->getImageUrlByFileId($shopConfig['logo_id'])}}"
                         @else
                         src="{{$theme::asset('img/shop-logo@lg.png')}}"
                        @endif
                    >
                </a>
            </h2>
            <button type="button" class="xe-shop-btn-search xe-hidden-md xe-hidden-lg"><i class="xi-search"></i><span class="xe-sr-only">검색</span></button>
        </div>
    </section>
    @include($theme::view('gnb'))
    <section class="search">
        <div class="container">
            <h2 class="xe-sr-only">검색</h2>
            <!-- [D] 활성화 시 active 클래스 추가 부탁드립니다-->
            <div class="xe-shop-search">
                <div class="search-box">
                    <div class="search-box-border">
                        <input type="search" class="search-box-input">
                        <button class="btn-search"><span class="xe-sr-only">검색하기</span><i class="xi-search"></i></button>
                    </div>
                </div><!-- //search-box  -->
                <div class="list-box">
                    <div class="search-keyword">
                        <div class="search-keyword-btn">
                            <!--[D] 활성화시 active 클래스 추가 부탁드립니다.  -->
                            <button type="button" class="active">최근 검색어</button>
                            <button type="button">인기 검색어</button>
                        </div>
                        <div class="search-keyword-list">
                            <!-- [D] 최근 검색어 리스트 display: none/block 하셔도 됩니다  -->
                            <ul>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                                <li><a href="#">dddddddddddddddddddddddddddddddddddddddddd</a></li>
                            </ul>
                        </div>
                        <div class="search-close">
                            <button class="xi-user-plus"></button>
                            <button type="button" class="btn-search-close">닫기</button>
                        </div>
                    </div>
                    <div class="search-preview">
                        <div class="search-preview-list">
                            <ul>
                                <li><a href="#">aa<b>aaa</b></a></li>
                                <li><a href="#">aa<b>aaa</b></a></li>
                                <li><a href="#">aa<b>aaa</b></a></li>
                                <li><a href="#">aa<b>aaa</b></a></li>
                                <li><a href="#">aa<b>aaa</b></a></li>
                                <li><a href="#">aa<b>aaa</b></a></li>
                            </ul>

                        </div>
                        <div class="search-close">
                            <button type="button" class="btn-search-close">닫기</button>
                        </div>
                    </div>
                </div><!-- //list-box  -->
            </div><!-- //xe-shop-search  -->
        </div>
    </section>

    <!-- // 위에부분 개발하고 지어줘요오오오 끝 -->

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
            <small>© 2018 XE Factory.com, Inc</small>
        </div>

    </footer>

</div>
