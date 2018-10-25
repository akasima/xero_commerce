{{-- script --}}
{{ app('xe.frontend')->js([
    'assets/vendor/bootstrap/js/bootstrap.min.js'
])->load() }}

{{-- stylesheet --}}
{{ app('xe.frontend')->css([
    'assets/vendor/bootstrap/css/bootstrap.min.css',
    $theme::asset('css/theme.css')
])->load() }}

{{-- inline style --}}
{{ app('xe.frontend')->html('theme.style')->content("
<style>
    body {
        padding-top: 50px;
        padding-bottom: 20px;
    }
</style>
")->load() }}

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url()->to(\Xpressengine\Plugins\XeroCommerce\Plugin::XERO_COMMERCE_URL_PREFIX) }}">{{ $config->get('logo_title', 'Xpressengine') }}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

            @include($theme::view('gnb'))

            <div class="navbar-form navbar-right">
                <ul class="navbar-nav navbar-expand">
                    @if(auth()->check())
                        <a href="{{ route('logout') }}">로그아웃</a>
                        <li role="separator" class="divider"></li>
                        <a href="{{ route('user.settings') }}">마이페이지</a>
                        <li role="separator" class="divider"></li>
                    @else
                        <a href="{{ route('login') }}">로그인</a>
                        <li role="separator" class="divider"></li>
                    @endif

                    <a href="{{route('xero_commerce::order.index')}}">주문내역</a>
                    <li role="separator" class="divider"></li>
                    <a href="{{route('xero_commerce::cart.index')}}">장바구니</a>
                    <li role="separator" class="divider"></li>
                </ul>
            </div>
        </div><!--/.navbar-collapse -->
    </div>
</nav>

@if($config->get('show_spot', 'hide') === 'show')
<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
    <div class="container">
        <h1>{{ $config->get('spot_title', 'Xpressengine') }}</h1>
        <p>{!! nl2br($config->get('spot_content', 'This theme is the implementation of <a href="http://getbootstrap.com/examples/jumbotron/">Bootstrap Jumbotron Template</a>.')) !!}</p>

        @if($config->get('spot_image') !== null)
        <p class="lead">
                <img src="{{ $config->get('spot_image.path') }}">
        </p>
        @endif

    </div>
</div>
@endif

<div class="container" style="margin-top:20px">
    <div id="sub-container">
        {!! $content !!}
    </div>

    <hr>

    <footer>
        <p>© 2016 Company, Inc.</p>
        <div class="">

        </div>
    </footer>
</div>
