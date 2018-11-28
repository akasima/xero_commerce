{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/EventWidget/Skins/Common/assets/style.css')->load() }}

<section class="section-event">
 	<div class="inner-main">
        <h2 class="title-event">EVENT</h2>
        <ul class="list-event">
            <li class="item-event">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['left']->getSlug()]) }}" class="link-event">
                    <span class="thumnail" style="background-image:url('{{ $products['left']->getThumbnailSrc() }}')"></span>
                    <div class="box-content">
                        <strong>{{ $products['left']->name }}</strong>
                        <span class="info">KNIT 이벤트 내용 KNIT 이벤트 내용 KNIT 이벤 트 내용 KNIT 이벤트 내용 KNIT 이벤트 내용 KNI T 이벤트</span>
                    </div>
                </a>
            </li>
			<li class="item-event">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['left']->getSlug()]) }}" class="link-event">
                    <span class="thumnail" style="background-image:url('{{ $products['left']->getThumbnailSrc() }}')"></span>
                    <div class="box-content">
                        <strong>{{ $products['left']->name }}</strong>
                        <span class="info">KNIT 이벤트 내용 KNIT 이벤트 내용 KNIT 이벤 트 내용 KNIT 이벤트 내용 KNIT 이벤트 내용 KNI T 이벤트</span>
                    </div>
                </a>
            </li>
            <li class="item-event">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['left']->getSlug()]) }}" class="link-event">
                    <span class="thumnail" style="background-image:url('{{ $products['left']->getThumbnailSrc() }}')"></span>
                    <div class="box-content">
                        <strong>{{ $products['left']->name }}</strong>
                        <span class="info">KNIT 이벤트 내용 KNIT 이벤트 내용 KNIT 이벤 트 내용 KNIT 이벤트 내용 KNIT 이벤트 내용 KNI T 이벤트</span>
                    </div>
                </a>
            </li>
            <li class="item-event">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['left']->getSlug()]) }}" class="link-event">
                    <span class="thumnail" style="background-image:url('{{ $products['left']->getThumbnailSrc() }}')"></span>
                    <div class="box-content">
                        <strong>{{ $products['left']->name }}</strong>
                        <span class="info">KNIT 이벤트 내용 KNIT 이벤트 내용 KNIT 이벤 트 내용 KNIT 이벤트 내용 KNIT 이벤트 내용 KNI T 이벤트</span>
                    </div>
                </a>
            </li>
            <li class="item-event">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['left']->getSlug()]) }}" class="link-event">
                    <span class="thumnail" style="background-image:url('{{ $products['left']->getThumbnailSrc() }}')"></span>
                    <div class="box-content">
                        <strong>{{ $products['left']->name }}</strong>
                        <span class="info">KNIT 이벤트 내용 KNIT 이벤트 내용 KNIT 이벤 트 내용 KNIT 이벤트 내용 KNIT 이벤트 내용 KNI T 이벤트</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</section>

<!-- 기존 소스
<div class="xe-shop">
    <div class="container">
        <h2 class="xe-shop-wiget-title">EVENT</h2>
        <div class="cross-list">
            <div class="large_img">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['left']->getSlug()]) }}">
                    <img src="{{ $products['left']->getThumbnailSrc() }}" style="width: 367px; height: 490px;">
                    <div class="inner_box">
                        <strong>{{ $products['left']->name }}</strong>
                        <p>KNIT 이벤트 내용 KNIT 이벤트 내용 KNIT 이벤 트 내용 KNIT 이벤트 내용 KNIT 이벤트 내용 KNI T 이벤트</p>
                    </div>
                </a>
            </div>
            <div class="small_box">
                <div class="small_img">
                    <a href="{{ route('xero_commerce::product.show', ['slug' => $products['center_up']->getSlug()]) }}">
                        <img src="{{ $products['center_up']->getThumbnailSrc() }}" style="width: 367px; height: 238px;">
                        <div class="inner_box">
                            <strong>{{ $products['center_up']->name }}</strong>
                            <p>KNIT 이벤트 내용 KNIT 이벤트 내용 KNIT 이벤 트 내용 KNIT 이벤트 내용 KNIT 이벤트 내용 KNI T 이벤트</p>
                        </div>
                    </a>
                </div>
                <div class="small_img">
                    <a href="{{ route('xero_commerce::product.show', ['slug' => $products['center_down']->getSlug()]) }}">
                        <img src="{{ $products['center_down']->getThumbnailSrc() }}" style="width: 367px; height: 238px;">
                        <div class="inner_box">
                            <strong>{{ $products['center_down']->name }}</strong>
                            <p>KNIT 이벤트 내용 KNIT 이벤트 내용 KNIT 이벤 트 내용 KNIT 이벤트 내용 KNIT 이벤트 내용 KNI T 이벤트</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="large_img">
                <a href="{{ route('xero_commerce::product.show', ['slug' => $products['right']->getSlug()]) }}">
                    <img src="{{ $products['right']->getThumbnailSrc() }}" style="width: 367px; height: 490px;">
                    <div class="inner_box">
                        <strong>{{ $products['right']->name }}</strong>
                        <p>KNIT 이벤트 내용 KNIT 이벤트 내용 KNIT 이벤 트 내용 KNIT 이벤트 내용 KNIT 이벤트 내용 KNI T 이벤트</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
-->