<section class="menu">
    <div class="container">
        <h2 class="xe-sr-only">메뉴</h2>
        <ul class="xe-shop-menu">
            @foreach(menu_list($config->get('gnb')) as $menu)
                <li @if($menu['selected']) class="active" @endif><a href="{{ url($menu['url']) }}" target="{{ $menu['target'] }}">{{ $menu['link'] }}</a>
                <ul>
                    <li><a href="#">1 depth depth depth depth depth depth depth depth</a></li>
                    <li><a href="#">1 depth</a></li>
                    <li><a href="#">1 depth</a></li>
                    <li><a href="#">1 depth</a></li>
                    <li><a href="#">1 depth</a></li>
                    <li><a href="#">1 depth</a></li>
                </ul>
                </li>
            @endforeach
        </ul>
        <h2 class="xe-sr-only">카테고리</h2>
        <button type="button" class="xe-btn-category" onclick="toggleMenu()"><i class="xi-bars"></i><span class="xe-sr-only">전체 카테고리 열기</span></button>
        <!-- [D] 활성화 시 active 클래스 추가 부탁드립니다-->
        <div class="xe-shop-category">
            <div class="xe-shop-login-box xe-hidden-lg xe-hidden-md">
                @if(auth()->check())
                    <a href="{{ route('logout') }}">
                        <button class="xe-btn xe-btn-login">로그아웃</button>
                    </a>
                @else
                    <a href="{{ route('login') }}">
                        <button class="xe-btn xe-btn-login">로그인</button>
                    </a>
                @endif
                <button class="xe-btn-close" onclick="toggleMenu()"><span class="xe-sr-only">전체 메뉴 닫기</span><i class="xi-close-thin"></i></button>
            </div>
            <div class="xe-shop-btn-box xe-hidden-lg xe-hidden-md">
                <div>
                    <a href="{{route('xero_commerce::cart.index')}}">장바구니</a>
                    <a href="{{route('xero_commerce::order.index')}}">주문조회</a>
                </div>
                <div>
                    <a href="{{ route('user.settings') }}">마이페이지</a>
                    <a href="#">공지사항</a>
                </div>
            </div>
            <h3 class="xe-shop-category-caption xe-hidden-md xe-hidden-lg">CATEGORY</h3>
            <div class="xe-shop-category-wrap">
                <ul class="xe-shop-category-list">
                    <!-- [D] 열림시 open 클래스 추가 -->
                    @foreach(menu_list($config->get('gnb')) as $menu)
                        <li @if($menu['selected']) class="open" @endif onclick="toggleSubMenu(this)">
                            <h4 class="xe-shop-category-title"><a href="#">{{ $menu['link'] }}</a><i @if($menu['selected']) class="xi-angle-up-thin" @else class="xi-angle-down-thin" @endif></i></h4>
                            <ul>
                                @foreach($menu['children'] as $submenu)
                                    <li><a href="{{url($submenu['url'])}}">{{ $submenu['link'] }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</section>
<script>
    function toggleMenu () {
        toggleClass(".xe-shop-category", "active")
    }

    function toggleSubMenu (target) {
        toggleClass(target, "open")
        var icon = $(target).find("h4 i");
        toggleClass(icon, "xi-angle-down-thin");
        toggleClass(icon, "xi-angle-up-thin");
    }

    function toggleClass (target, className) {
        if($(target).hasClass(className)){
            $(target).removeClass(className);
        }else{
            $(target).addClass(className);
        }
    }
</script>
