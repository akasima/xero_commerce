<section class="xe-shop slider">
    <div class="container">
        <h2 class="xe-sr-only">비주얼 영역</h2>
        <div class="xe-spot-slider-view">
            <ul>
                @foreach ($images as $image)
                    <li><a href="#"><img src="{{ $image }}"></a></li>
                @endforeach
            </ul>
        </div>
        <div class="xe-spot-slider-pager">
            <button class="xe-btn-prev"><i class="xi-angle-left-thin"></i><span class="xe-sr-only">이전 보기</span></button>
            <button class="xe-btn-next"><i class="xi-angle-right-thin"></i><span class="xe-sr-only">다음 보기</span></button>
        </div>
        <ul class="xe-spot-slider-direction">
            <li class="active"><button></button></li>
            <li class=""><button></button></li>
            <li class=""><button></button></li>
        </ul>
    </div>
</section>
