{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/EventWidget/Skins/Common/assets/style.css')->load() }}

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
