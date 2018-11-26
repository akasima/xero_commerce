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
->content('width=device-width, initial-scale=1.0')->load()
}}
@php
    $shopConfig = XeConfig::getOrNew(\Xpressengine\Plugins\XeroCommerce\Plugin::getId());
@endphp

<div class="xe-shop">
    <h1 class="xe-sr-only">xe 쇼핑</h1>
    <!-- 기본 ui
      scss/user/_skin.scss
      include/default_header.html
      include/default_logo.html
      include/default_menu.html
      include/default_search.html
      include/default_slider.html
    -->
    <section class="header">
        <!-- [D] width를 제어하기 위해 사용하는 클래스입니다. 100% 넓이를 사용하는 위젯은 사용할 필요가 없습니다. -->
        <div class="container">
            <h2 class="xe-sr-only">상단 유틸</h2>
            <article class="xe-shop-notice xe-hidden-xs xe-hidden-sm">
                <h3>notice</h3>
                <div class="notice-view">
                    <ul>
                        @foreach ($notices as $notice)
                            <li><a href="{{ $urlHandler->getShow($notice) }}">{{ $notice->title }} </a></li>
                        @endforeach
                    </ul>
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
                    @else
                        <li><a href="{{ route('login') }}">로그인</a></li>
                        <li><a href="{{route('auth.register') }}">회원가입</a></li>
                    @endif
                    <li><a href="{{route('xero_commerce::cart.index')}}">장바구니</a></li>
                    <li><a href="{{route('xero_commerce::order.list')}}">주문조회</a></li>
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
    <section class="logo">
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
    <div class="container" style="margin-top:20px">
        <div id="sub-container">
            {!! $content !!}
        </div>

        <hr>
        <footer class="footer">
            <section class="xe-company-info">
                <div class="container">
                    <div class="xe-company-info-article">
                        <p>
                            <b>상호명</b>
                            <span>{{$shopConfig['companyName']}}</span>
                        </p>
                        <p>
                            <b>대표자</b>
                            <span>{{$shopConfig['ceoName']}}</span>
                        </p>
                        <p>
                            <b>사업자등록번호</b>
                            <span>{{$shopConfig['companyNumber']}}<a href="#">[사업자정보확인]</a></span>
                        </p>
                        <p>
                            <b>통신판매업</b>
                            <span>신고 {{$shopConfig['communicationMarketingNumber']}}</span>
                        </p>
                    </div>
                    <div class="xe-company-info-article">
                        <p>
                            <b>주소</b>
                            <span>({{$shopConfig['zipCode']}}) {{$shopConfig['address']}}</span>
                        </p>
                        <p>
                            <b>대표전화</b>
                            <span>{{$shopConfig['telNumber']}}</span>
                        </p>
                    </div>
                    <div class="xe-company-info-article">
                        <p>
                            <b>개인정보관리책임자</b>
                            <span>{{$shopConfig['informationCharger']}}</span>
                        </p>
                        <p>
                            <b>이메일</b>
                            <span>{{$shopConfig['email']}}</span>
                        </p>
                    </div>
                </div>
            </section>
            <section class="copyright">
                <div class="container">
                    <p>Copyright &copy; 2016 {{$shopConfig['companyName']}} All rights reserved.&nbsp;&nbsp;&nbsp;<br class="xe-hidden-md xe-hidden-lg">MADE BY XE</p>
                </div>
            </section>
        </footer>
    </div>
</div>
