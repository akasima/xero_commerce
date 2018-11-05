{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/SlideWidget/Skins/Common/assets/css/style.css')->load() }}

<div class="xe-shop slider">
    <div class="container">
        <h2 class="xe-shop-wiget-title">MD 추천상품</h2>
        <div class="xe-shop-slider">
            <div class="xe-shop-slider-view">
                <ul>
                    <li class="active"><a href="#">
                            <img src="{{ \Xpressengine\Plugins\XeroCommerce\Components\Widget\SlideWidget\Skins\Common\CommonSkin::asset('img/tmp_slider.jpg') }}" alt="">
                            <!-- [D] 엔터는 1회 한하여 사용할수 있도록 한다. 한줄에 들어가는 한글 최대 갯수 / 영문  -->
                            <div class="xe-shop-slider-caption">
                                <p class="xe-shop-slider-title">연말선물 끝판왕<br>브랜드 ITEM</p>
                                <p class="xe-shop-slider-comment">MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천
                                    MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천MD추천 MD추천 MD추천 MD추천</p>
                            </div>
                        </a></li>
                    <!-- [D] 가운대 위치할 경우 active 클래스 추가 부탁드립니다.  -->
                    <li class><a href="#">
                            <img src="{{ \Xpressengine\Plugins\XeroCommerce\Components\Widget\SlideWidget\Skins\Common\CommonSkin::asset('img/tmp_slider.jpg') }}" alt="">
                            <!-- [D] 엔터는 1회 한하여 사용할수 있도록 한다. 한줄에 들어가는 한글 최대 갯수 / 영문  -->
                            <div class="xe-shop-slider-caption">
                                <p class="xe-shop-slider-title">연말선물 끝판왕<br>브랜드 ITEM</p>
                                <p class="xe-shop-slider-comment">MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천
                                    MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천MD추천 MD추천 MD추천 MD추천</p>
                            </div>
                        </a></li>
                    <li><a href="#">
                            <img src="{{ \Xpressengine\Plugins\XeroCommerce\Components\Widget\SlideWidget\Skins\Common\CommonSkin::asset('img/tmp_slider.jpg') }}" alt="">
                            <!-- [D] 엔터는 1회 한하여 사용할수 있도록 한다. 한줄에 들어가는 한글 최대 갯수 / 영문  -->
                            <div class="xe-shop-slider-caption">
                                <p class="xe-shop-slider-title">연말선물 끝판왕<br>브랜드 ITEM</p>
                                <p class="xe-shop-slider-comment">MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천
                                    MD추천 MD추천 MD추천 MD추천 MD추천 MD추천 MD추천MD추천 MD추천 MD추천 MD추천</p>
                            </div>
                        </a></li>
                </ul>
            </div>
            <button type="button" class="xe-btn xe-btn-slider-prev"><i class="xi-angle-left-thin"></i></button>
            <button type="button" class="xe-btn xe-btn-slider-next"><i class="xi-angle-right-thin"></i></button>
        </div>
    </div>
</div>

<script>
    $('.xe-shop-slider-view').on('resize', function () {
        console.log('1111');
    });
</script>
