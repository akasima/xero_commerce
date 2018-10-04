<template>
    <div class="row">
        <div class="col-lg-3">
            <div class="row">
                <div class="col-lg-12 text-center text-white bg-dark">
                    {{user.display_name}}님의 <br>
                    회원등급
                    <div class="circle text-dark">
                        <p>VIP</p>
                    </div>
                </div>
                <div class="col-lg-12 card">
                    <div class="card-header">
                        <h3>적립금</h3>
                    </div>
                    <div class="card-content">
                        원
                    </div>
                </div>
                <div class="col-lg-12 card">
                    <div class="card-header">
                        <h3>쿠폰</h3>
                    </div>
                    <div class="card-content">
                        장
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            진행중인 주문
                        </div>
                        <div class="card-content">
                            <div class="row">
                                <div class="col">
                                    <h3>입금 대기중</h3>
                                    <p>
                                        {{dashboard['결제대기']}}
                                    </p>
                                    <div style="position:absolute; right:-10px; top:25px">
                                        <i class="xi-angle-right"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h3>배송준비중</h3>
                                    <p>
                                        {{dashboard['상품준비']}}
                                    </p>
                                    <div style="position:absolute; right:-10px; top:25px">
                                        <i class="xi-angle-right"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h3>배송중</h3>
                                    <p>
                                        {{dashboard['배송중']}}
                                    </p>
                                    <div style="position:absolute; right:-10px; top:25px">
                                        <i class="xi-angle-right"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h3>배송완료</h3>
                                    <p>
                                        {{dashboard['배송완료']}}
                                    </p>
                                </div>
                                <div class="col">
                                    <p>
                                        교환중 : {{dashboard['교환중']}}
                                    </p>
                                    <p>
                                        환불중 : {{dashboard['환불중']}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12" id="direct">
                    <div class="row text-center">
                        <div class="col-lg-12">
                            그외 링크
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="xi-desktop"></i> <br>
                                    주문내역조회
                                </div>
                                <div class="card-content">
                                    주문내역을 조회합니다
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="xi-user"></i> <br>
                                    회원정보
                                </div>
                                <div class="card-content">
                                    회원정보를 조회/수정합니다.
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="xi-basket"></i> <br>
                                    관심상품
                                </div>
                                <div class="card-content">
                                    내 관심상품 보기
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="xi-piggy-bank"></i> <br>
                                    적립금 관리
                                </div>
                                <div class="card-content">
                                    적립금 보기
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="xi-coupon"></i> <br>
                                    쿠폰 관리
                                </div>
                                <div class="card-content">
                                    쿠폰 확인
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <i class="xi-border-color"></i> <br>
                                    게시물 관리
                                </div>
                                <div class="card-content">
                                    게시물 확
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "OrderDashComponent",
    props: [
      'dashboard', 'user', 'userInfo'
    ],
    data () {
      return {
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
    #direct .col-lg-4 .card {
        height:100px
    }
</style>