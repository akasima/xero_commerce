<template>
    <div class="xe-shop mypage">
        <div class="container">
            <div class="mypage-wrap">

                <h1 class="page-title xe-hidden-sm xe-hidden-xs">마이페이지</h1>
                <div class="mypage-box">
                    <section class="mypage-membership">
                        <h2 class="mypage-membership-title"><span>{{user.display_name}}</span>님의 <br class="xe-hidden-sm xe-hidden-xs">회원등급</h2>
                        <div class="mypage-membership-rating">VIP</div>
                    </section><!-- //mypage-membership -->
                    <!--<div class="mypage-accumulation">-->
                        <!--<section class="mypage-accumulation-point">-->
                            <!--<h3 class="mypage-accumulation-title">적립금</h3>-->
                            <!--<div class="mypage-accumulation-number"><p>999,999,999원</p></div>-->
                        <!--</section>-->
                        <!--<section class="mypage-accumulation-point">-->
                            <!--<h3 class="mypage-accumulation-title">쿠폰</h3>-->
                            <!--<div class="mypage-accumulation-number"><p>999,999,999장</p></div>-->
                        <!--</section>-->
                    <!--</div>&lt;!&ndash; //mypage-accumulation &ndash;&gt;-->
                </div><!-- //mypage-box -->
                <div class="mypage-box">
                    <section class="mypage-status">
                        <h3 class="mypage-status-title">진행중인 주문</h3>
                        <div class="mypage-status-content">
                            <ul class="mypage-status-step">
                                <li @click.prevent="url(listUrl+'?code=1')">
                                    <h4 class="mypage-status-step-title">입금 대기중</h4>
                                    <p class="mypage-status-step-count">{{dashboard['결제대기']}}</p>
                                    <i class="mypage-status-step-icon xi-angle-right"></i>
                                </li>
                                <li @click.prevent="url(listUrl+'?code=2')">
                                    <h4 class="mypage-status-step-title">배송준비중</h4>
                                    <p class="mypage-status-step-count">{{dashboard['상품준비']}}</p>
                                    <i class="mypage-status-step-icon xi-angle-right"></i>
                                </li>
                                <li @click.prevent="url(listUrl+'?code=3')">
                                    <h4 class="mypage-status-step-title">배송중</h4>
                                    <p class="mypage-status-step-count">{{dashboard['배송중']}}</p>
                                    <i class="mypage-status-step-icon xi-angle-right"></i>
                                </li>
                                <li @click.prevent="url(listUrl+'?code=4')">
                                    <h4 class="mypage-status-step-title">배송완료</h4>
                                    <p class="mypage-status-step-count">{{dashboard['배송완료']}}</p>
                                </li>
                            </ul>
                            <ul class="mypage-status-total">
                                <li>
                                    <b>취소</b>
                                    <p><span>{{dashboard['취소중']+dashboard['취소완료']}}</span>건</p>
                                </li>
                                <li>
                                    <b>교환</b>
                                    <p><span>{{dashboard['교환중']+dashboard['교환완료']}}</span>건</p>
                                </li>
                                <li>
                                    <b>반품</b>
                                    <p><span>{{dashboard['환불중']+dashboard['환불완료']}}</span>건</p>
                                </li>
                            </ul>
                        </div>
                    </section><!-- //mypage-status -->
                    <section class="mypage-util">
                        <h4 class="xe-sr-only">그외 링크</h4>
                        <ul>
                            <li>
                                <a href="#" @click.prevent="url(listUrl)">
                                    <p>
                                        <i class="xi-desktop"></i> <br class="xe-hidden-lg xe-hidden-md">
                                        주문내역조회
                                    </p>
                                    <div class="mypage-util-caption xe-hidden-sm xe-hidden-xs">고객님께서 주문하신 상품의 주문내역을 확인하실 수 있습니다.</div>
                                </a>
                            </li>
                            <li>
                                <a href="#" @click.prevent="url('/user')">
                                    <p>
                                        <i class="xi-user"></i> <br class="xe-hidden-lg xe-hidden-md">
                                        회원 정보
                                    </p>
                                    <div class="mypage-util-caption xe-hidden-sm xe-hidden-xs">회원이신 고객님의 개인정보를 관리하는 공간입니다.</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <p>
                                        <i class="xi-basket"></i> <br class="xe-hidden-lg xe-hidden-md">
                                        관심상품
                                    </p>
                                    <div class="mypage-util-caption xe-hidden-sm xe-hidden-xs">관심상품으로 등록하신 상품의 목록을 보여드립니다.</div>
                                </a>
                            </li>
                            <li v-if="discountOption">
                                <a href="#">
                                    <p>
                                        <i class="xi-piggy-bank"></i> <br class="xe-hidden-lg xe-hidden-md">
                                        적립금 관리
                                    </p>
                                    <div class="mypage-util-caption xe-hidden-sm xe-hidden-xs">적립금은 상품 구매 시 사용하실 수 있습니다. <br>적립된 금액은 현금으로 환불되지 않습니다.</div>
                                </a>
                            </li>
                            <li v-if="discountOption">
                                <a href="#">
                                    <p>
                                        <i class="xi-coupon"></i> <br class="xe-hidden-lg xe-hidden-md">
                                        쿠폰 관리
                                    </p>
                                    <div class="mypage-util-caption xe-hidden-sm xe-hidden-xs">고객님이 보유하고 계신 쿠폰내역을 보여드립니다.</div>
                                </a>
                            </li>
                            <li v-if="discountOption">
                                <a href="#">
                                    <p>
                                        <i class="xi-border-color"></i> <br class="xe-hidden-lg xe-hidden-md">
                                        게시물 관리
                                    </p>
                                    <div class="mypage-util-caption xe-hidden-sm xe-hidden-xs">고객님께서 작성하신 글을 한눈에 관리하실 수 있습니다.</div>
                                </a>
                            </li>
                        </ul>
                    </section><!-- //mypage-util -->
                </div><!-- //mypage-box -->


            </div><!-- //shipping-wrap -->
        </div><!-- //container  -->
    </div><!-- //shipping -->
</template>

<script>
  export default {
    name: "OrderDashComponent",
    props: [
      'dashboard', 'user', 'userInfo', 'listUrl'
    ],
    data () {
      return {
          discountOption: false
      }
    },
    methods: {
      url (url) {
        document.location.href=url
        console.log(url)
      }
    }
  }
</script>

<style scoped>
.card-content .col {
    text-align: center;
}
.card-content .col p{
    font-size: 15pt;
    font-weight: bold;
}
.circle {
    background: white;
    border-radius: 100px;
    color: white;
    height: 100px;
    font-weight: bold;
    width: 100px;
    display: table;
    margin: 20px auto;
}
.circle p {
    vertical-align: middle;
    display: table-cell;
    font-size: 20pt
}
    #direct .card {
        height:250px;
    }
    #direct .card .card-content {
        padding-top:90px;
    }
</style>
