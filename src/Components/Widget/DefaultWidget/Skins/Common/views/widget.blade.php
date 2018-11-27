<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<section class="xero-main-slider-wrap">
    <div class="xero-main-slider">
        <div>
            <a href="#link" class="slider-item" style="background-image:url('https://upload.wikimedia.org/wikipedia/commons/f/f4/180717_%EC%97%B4%EB%A6%B0%EC%9D%8C%EC%95%85%ED%9A%8C_%ED%8A%B8%EC%99%80%EC%9D%B4%EC%8A%A4_02.jpg')">
            	<div class="inner-main">
                	<div class="text_slide">
                        <strong class="title"><span>아몬드를 품은 김스낵</span></strong>
                        <p class="text">아몬드를 품은 김스낵은 전라남도의 청정바다에서 양식된 깨끗한 김에 아몬드와 참깨 그리고 천연 조미액이 어우러진 바삭하고 맛있는 간식입니다.</p>
                    </div>
                </div>
            </a>
        </div>
        <div>
            <a href="#link" class="slider-item" style="background-image:url('https://social.lge.co.kr/wp-content/uploads/2017/09/twice_%EB%8C%80%EB%AC%B8.jpg')">
                <div class="inner-main">
                    <div class="text_slide">
                        <strong class="title"><span>웅빈님</span></strong>
                        <p class="text">도커때문에 스트레스 받지 말아요</p>
                    </div>
                </div>
            </a>
        </div>
        <div>
            <a href="#link" class="slider-item" style="background-image:url('http://www.mbcsportsplus.com/data/board/attach/2018/10/20181021103500_bsqaxiwa.jpg')">
                <div class="inner-main">
                    <div class="text_slide">
                        <strong class="title"><span>영웅님</span></strong>
                        <p class="text">이쁜 사랑하세요</p>
                    </div>
                </div>
            </a>
        </div>
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
                    dots: false
                }
            }
        ]
    });
});
</script>