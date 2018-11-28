<section class="xero-main-slider-wrap">
    <div class="xero-main-slider">
        @foreach($items as $item)
            <div class="slider-item" style="background-image:url('{{ $item->imageUrl() }}')">
                <a href="{{ url($item->link) }}" target="{{ $item->link_target }}">
                    <strong class="title"><span>{{ $item->title }}</span></strong>
                    <p class="content">{!! nl2br($item->content) !!}</p>
                </a>
            </div>
        @endforeach
    </div>
</section>

<script>
$(document).ready(function(){
    $('.xero-main-slider').slick({
        dots: true,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    dots: false,
                    arrows: false,
                    centerMode: true
                }
            }
        ]
    });
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
