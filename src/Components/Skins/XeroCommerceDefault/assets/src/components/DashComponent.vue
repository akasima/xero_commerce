<template>
    <div class="row">
        <div class="col-lg-3">
            <div class="row">
                <div class="col-lg-12">
                    님의 <br>
                    회원등급
                </div>
                <div class="col-lg-12">
                    <div>
                        <h3>적립금</h3>
                    </div>
                    <div>
                        원
                    </div>
                </div>
                <div class="col-lg-12">
                    <div>
                        <h3>쿠폰</h3>
                    </div>
                    <div>
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
                                        {{orderStat[0]}}
                                    </p>
                                </div>
                                <div class="col">
                                    <h3>배송준비중</h3>
                                    <p>
                                        {{orderStat[1]}}
                                    </p>
                                </div>
                                <div class="col">
                                    <h3>배송중</h3>
                                    <p>
                                        {{orderStat[2]}}
                                    </p>
                                </div>
                                <div class="col">
                                    <h3>배송완료</h3>
                                    <p>
                                        {{orderStat[3]}}
                                    </p>
                                </div>
                                <div class="col">
                                    <h3></h3>
                                    <p>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            그외 링크
                        </div>
                        <div class="col-lg-4">
                            주문내역조회
                        </div>
                        <div class="col-lg-4">
                            회원정보
                        </div>
                        <div class="col-lg-4">
                            관심상품
                        </div>
                        <div class="col-lg-4">
                            적립금 관리
                        </div>
                        <div class="col-lg-4">
                            쿠폰 관리
                        </div>
                        <div class="col-lg-4">
                            게시물 관리
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
    data () {
      return {
        orderStat: [
            1,
            2,
            1,
            2
        ]
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
</style>