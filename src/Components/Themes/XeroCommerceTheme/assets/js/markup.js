

$(window).load(function (){

  function noticeSet() { // 1회만 실행
    this.target = $('.notice-view li');
    this.targetH = $('.notice-view').height(); // 이동 거리가 될 view 의 높이
    this.targetL = this.target.length; // 엘리먼트 갯수
    this.targetArray  = []; // top 값 담아둘 배열
    //맨마지막 엘리먼트를 맨위(-1) 로 올림다
    
    for (var i = -1; i < (this.targetL -1); i++) { // top 값계산을 위한 for -1 부터 시작이 중요
      this.targetArray.push(i * this.targetH); // 굳이 배열을 써야 할까?
      this.target.eq(i+1).css('top', (this.targetArray[i+1] + 15)); // 기본 li 좌표를 찍는다
    }
    this.target.eq(this.targetL-1).css('top', this.targetH * -1); //마지막 엘리먼트는 제일 위로 올려서 -1 일때 대응하도록 한다
    
    setInterval( function (){
      noticeMov(this.targetArray, this.target, this.targetH);
    } , 2000);
  }
  function noticeMov(array, target, height) { // li 이동 관련;
    for (var i = 0; i < array.length; i++) { // 엘리먼트 갯수 만큼
      this.pos = target.eq(i).position().top; //엘리먼트 top 값을 저장
      if (this.pos == -(height)) { // top 값이 -15이면
        target.eq(i).css({
          'top': array[array.length-1],
          'transition' : 'none' // 마지막 칸으로 이동시 transition 무효화
        }); // 끝으로 이동
      } else { // 그외의 것들은
        target.eq(i).css({
          'top': (this.pos - 15),
          'transition' : '' // 무효화를 삭제 하여 css 속성을 사용하도록 한다
        }); // -15 만큼 위로 이동
      }
    }
  };
  
  noticeSet();
  
  $('.xe-btn-category').on('click', function (){ // 카테고리 메뉴 토글
    $('.xe-shop-category').toggleClass('active');
  });
  
  $(window).on('scroll', function(){
    var menuTop = $(this).scrollTop();
    
    if( menuTop > 240) {
      $('.xe-shop .menu').addClass('fixed');
    } else {
      $('.xe-shop .menu').removeClass('fixed');
    }
    
  });
  
  //category
  $('.xe-shop-category-title a').on('click', function(){
    $('.xe-shop-category-list li').removeClass('open');
    $(this).parents('li').addClass('open');
  });
  
  //category close
  $('.xe-shop-login-box .xe-btn-close').on('click', function(){
    $('.xe-shop-category').removeClass('active');
  });
  
  // search close
  $('.btn-search-close').on('click', function (){
    $('.xe-shop-search').removeClass('active');
  });
  
  // detail options
  $('.btn-option-toggle').on('click', function (){
    $('.product-info-options').toggleClass('on');
  });
  
  // payment
  $('input[name$=pay-type]').change(function(){
    var radioButtons = $("input:radio[name='pay-type']");
    var selectedIndex = radioButtons.index(radioButtons.filter(':checked'));
    $('.pay-type').css('display', 'none').eq(selectedIndex).css('display', 'block');
  });
  
  
  
});