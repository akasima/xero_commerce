<section class="section-recommend">
    <h3 class="title-recommend">MD 추천상품</h3>

    <div class="recommend-slider">
        @foreach($items as $item)
        <a class="slider-item" href="{{ url($item->link) }}" target="{{ $item->link_target }}">
        	<span class="thumbnail" style="background-image:url('{{ $item->imageUrl() }}')"></span>
            <div class="text_slide">
                <strong class="title"><span>{{ $item->title }}</span></strong>
                <p class="text">{!! nl2br($item->content) !!}</p>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/RecommendSlider/assets/recommendSlider.css')->load() }}
<script>
$(document).ready(function(){
    $('.recommend-slider').slick({
        dots: true,
		prevArrow: '<button type="button" class="slick-prev"><i class="xi-angle-left"></i></button>',
    	nextArrow: '<button type="button" class="slick-next"><i class="xi-angle-right"></i></button>',
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    dots: false,
                    arrows: false
                }
            }
        ]
    });
    @if(count($items)==1){
        $(".recommend-slider ul.slick-dots").css("display","none")
        }
    @endif
});
</script>

{{-- script --}}
{{ app('xe.frontend')->js([
    'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js'
])->load() }}

{{-- stylesheet --}}
{{ app('xe.frontend')->css([
    'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css'
])->load() }}
