<section class="xero-main-slider-wrap">
    <div class="xero-main-slider">
        @foreach($items as $item)
        <a class="slider-item" href="{{ url($item->link) }}" target="{{ $item->link_target }}" style="background-image:url('{{ $item->imageUrl() }}')">
            <div class="inner-main">
             	<div class="text_slide">
                    <strong class="title"><span>{{ $item->title }}</span></strong>
                    <p class="text">{!! nl2br($item->content) !!}</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>

<script>
$(document).ready(function(){
    $('.xero-main-slider').slick({
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
        $(".xero-main-slider ul.slick-dots").css("display","none")
    }
    @endif
});
</script>

{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/MainSlider/assets/mainSlider.css')->load() }}
{{-- script --}}
{{ app('xe.frontend')->js([
    'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js'
])->load() }}

{{-- stylesheet --}}
{{ app('xe.frontend')->css([
    'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css'
])->load() }}
