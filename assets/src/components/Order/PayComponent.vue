<template>
    <div class="table-wrap">
        <h4 class="table-type-title">결제 정보 입력</h4>
        <!--<div class="table-caption">-->
            <!--<label class="xe-label">-->
                <!--<input type="checkbox">-->
                <!--<span class="xe-input-helper"></span>-->
                <!--<span class="xe-label-text">지금 선택한 결제수단을 다음에도 사용</span>-->
            <!--</label>-->
        <!--</div>&lt;!&ndash; //table-caption &ndash;&gt;-->
        <button type="button" class="btn-cart-toggle xe-hidden-md xe-hidden-lg"><i class="xi-angle-up-thin"></i></button>
        <div class="table-type2">
            <div class="table-row">
                <div class="table-cell header">
                    일반 결제
                </div>
                <div class="table-cell btn-form">
                    <div class="table-cell-inside" v-for="(method,key) in payMethods" :key="key">
                        <label class="xe-label">
                            <input type="radio" checked="checked" name="pay-type" :value="key" v-model="payMethod">
                            <span class="xe-input-helper"></span>
                            <span class="xe-label-text">{{method}}</span>
                        </label>
                    </div><!-- //table-cell-inside -->
                </div>
            </div><!-- //table-row -->
            <!--<div class="table-row">-->
                <!--<div class="table-cell header xe-hidden-sm xe-hidden-xs">-->
                <!--</div>-->
                <!--<div class="table-cell type-cell">-->

                    <!--&lt;!&ndash; [D] 신용카드 &ndash;&gt;-->
                    <!--<div class="pay-type type" style="display: block">-->
                        <!--<div class="pay-cash-row">-->
                            <!--<div class="pay-cash-col">-->
                                <!--<label for="pay-info-card" class="pay-cash-label">카드 선택</label>-->
                                <!--<div class="xe-select-box xe-btn table-select input-150">-->
                                    <!--<label>선택하세요</label>-->
                                    <!--<select id="pay-info-card">-->
                                        <!--<option></option>-->
                                    <!--</select>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<div class="pay-cash-col">-->
                                <!--<label for="pay-info-installment" class="pay-cash-label">할부 선택</label>-->
                                <!--<div class="xe-select-box xe-btn table-select input-150">-->
                                    <!--<label>일시불</label>-->
                                    <!--<select id="pay-info-installment">-->
                                        <!--<option></option>-->
                                    <!--</select>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<button class="xe-btn pay-cash-btn xe-hidden-sm xe-hidden-xs">무이자 할부 안내</button>-->
                        <!--</div>&lt;!&ndash; //pay-cash-row &ndash;&gt;-->
                    <!--</div>&lt;!&ndash; //pay-type type &ndash;&gt;-->

                    <!--&lt;!&ndash; [D] 실시간 계좌이체  &ndash;&gt;-->
                    <!--<div class="pay-type type2" style="display: none">-->

                        <!--<div class="pay-cash-row">-->
                            <!--<div class="pay-cash-col">-->
                                <!--<label class="xe-label">-->
                                    <!--<input type="radio" checked="checked" name="pay-tax">-->
                                    <!--<span class="xe-input-helper"></span>-->
                                    <!--<span class="xe-label-text">개인소득공제용</span>-->
                                <!--</label>-->
                            <!--</div>-->
                            <!--<div class="pay-cash-col">-->
                                <!--<label class="xe-label">-->
                                    <!--<input type="radio" checked="checked" name="pay-tax">-->
                                    <!--<span class="xe-input-helper"></span>-->
                                    <!--<span class="xe-label-text">사업자증빙용</span>-->
                                <!--</label>-->
                            <!--</div>-->
                            <!--<div class="pay-cash-col">-->
                                <!--<label class="xe-label">-->
                                    <!--<input type="radio" checked="checked" name="pay-tax">-->
                                    <!--<span class="xe-input-helper"></span>-->
                                    <!--<span class="xe-label-text">미발행</span>-->
                                <!--</label>-->
                            <!--</div>-->
                        <!--</div>&lt;!&ndash; //pay-cash-row &ndash;&gt;-->
                        <!--<div class="pay-cash-row">-->
                            <!--<div class="pay-cash-col">-->
                                <!--<div class="xe-select-box xe-btn table-select input-150">-->
                                    <!--<label>현금영수증 카드번호</label>-->
                                    <!--<select>-->
                                        <!--<option></option>-->
                                        <!--<option></option>-->
                                    <!--</select>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<div class="pay-cash-col">-->
                                <!--<input type="text" class="xe-form-control input-55">-->
                                <!--<span class="margin-between">-</span>-->
                                <!--<input type="text" class="xe-form-control input-55">-->
                                <!--<span class="margin-between">-</span>-->
                                <!--<input type="text" class="xe-form-control input-55">-->
                                <!--<span class="margin-between">-</span>-->
                                <!--<input type="text" class="xe-form-control input-55">-->
                            <!--</div>-->
                        <!--</div>&lt;!&ndash; //pay-cash-row &ndash;&gt;-->

                    <!--</div>&lt;!&ndash; //pay-type type2 &ndash;&gt;-->

                    <!--&lt;!&ndash; [D] 무통장  &ndash;&gt;-->
                    <!--<div class="pay-type type3" style="display: none">-->
                        <!--<div class="pay-cash">-->
                            <!--<div class="pay-cash-top">-->
                                <!--<div class="pay-cash-row">-->
                                    <!--<div class="pay-cash-col">-->
                                        <!--<label for="pay-info-bank" class="pay-cash-label">은행 선택</label>-->
                                        <!--<div class="xe-select-box xe-btn table-select input-170">-->
                                            <!--<label>선택하세요</label>-->
                                            <!--<select id="pay-info-bank">-->
                                                <!--<option>KB국민</option>-->
                                                <!--<option>KEB하나</option>-->
                                                <!--<option>우리</option>-->
                                                <!--<option>신한</option>-->
                                                <!--<option>KDB산업</option>-->
                                                <!--<option>IBK기업</option>-->
                                                <!--<option>NH농협</option>-->
                                                <!--<option>SC제일</option>-->
                                                <!--<option>BNK부산</option>-->
                                                <!--<option>DGB대구</option>-->
                                                <!--<option>한국씨티</option>-->
                                                <!--<option>BNK경남</option>-->
                                                <!--<option>Sh수협</option>-->
                                                <!--<option>광주</option>-->
                                                <!--<option>전북</option>-->
                                                <!--<option>제주</option>-->
                                            <!--</select>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="pay-cash-col">-->
                                        <!--<label for="pay-info-name" class="pay-cash-label">입금자명</label>-->
                                        <!--<input type="text" id="pay-info-name" class="xe-form-control input-150">-->
                                    <!--</div>-->
                                <!--</div>&lt;!&ndash; //pay-cash-row &ndash;&gt;-->
                                <!--<div class="pay-cash-row">-->
                                    <!--<div class="pay-cash-col">-->
                                        <!--<label class="xe-label">-->
                                            <!--<input type="radio" checked="checked" name="pay-tax">-->
                                            <!--<span class="xe-input-helper"></span>-->
                                            <!--<span class="xe-label-text">개인소득공제용</span>-->
                                        <!--</label>-->
                                    <!--</div>-->
                                    <!--<div class="pay-cash-col">-->
                                        <!--<label class="xe-label">-->
                                            <!--<input type="radio" checked="checked" name="pay-tax">-->
                                            <!--<span class="xe-input-helper"></span>-->
                                            <!--<span class="xe-label-text">사업자증빙용</span>-->
                                        <!--</label>-->
                                    <!--</div>-->
                                    <!--<div class="pay-cash-col">-->
                                        <!--<label class="xe-label">-->
                                            <!--<input type="radio" checked="checked" name="pay-tax">-->
                                            <!--<span class="xe-input-helper"></span>-->
                                            <!--<span class="xe-label-text">미발행</span>-->
                                        <!--</label>-->
                                    <!--</div>-->
                                <!--</div>&lt;!&ndash; //pay-cash-row &ndash;&gt;-->

                                <!--<div class="pay-cash-row">-->
                                    <!--<div class="pay-cash-col">-->
                                        <!--<div class="xe-select-box xe-btn table-select input-150">-->
                                            <!--<label>현금영수증 카드번호</label>-->
                                            <!--<select>-->
                                                <!--<option></option>-->
                                                <!--<option></option>-->
                                            <!--</select>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="pay-cash-col">-->
                                        <!--<input type="text" class="xe-form-control input-55">-->
                                        <!--<span class="margin-between">-</span>-->
                                        <!--<input type="text" class="xe-form-control input-55">-->
                                        <!--<span class="margin-between">-</span>-->
                                        <!--<input type="text" class="xe-form-control input-55">-->
                                        <!--<span class="margin-between">-</span>-->
                                        <!--<input type="text" class="xe-form-control input-55">-->
                                    <!--</div>-->
                                <!--</div>&lt;!&ndash; //pay-cash-row &ndash;&gt;-->

                            <!--</div>-->
                            <!--<div class="pay-cash-bottom">-->
                                <!--<b class="pay-cash-title">환불계좌 정보</b>-->
                                <!--<div class="pay-cash-row">-->
                                    <!--<div class="pay-cash-col">-->
                                        <!--<label for="pay-info-bank2" class="pay-cash-label">은행 선택</label>-->
                                        <!--<div class="xe-select-box xe-btn table-select input-170">-->
                                            <!--<label>선택하세요</label>-->
                                            <!--<select id="pay-info-bank2">-->
                                                <!--<option>KB국민</option>-->
                                                <!--<option>KEB하나</option>-->
                                                <!--<option>우리</option>-->
                                                <!--<option>신한</option>-->
                                                <!--<option>KDB산업</option>-->
                                                <!--<option>IBK기업</option>-->
                                                <!--<option>NH농협</option>-->
                                                <!--<option>SC제일</option>-->
                                                <!--<option>BNK부산</option>-->
                                                <!--<option>DGB대구</option>-->
                                                <!--<option>한국씨티</option>-->
                                                <!--<option>BNK경남</option>-->
                                                <!--<option>Sh수협</option>-->
                                                <!--<option>광주</option>-->
                                                <!--<option>전북</option>-->
                                                <!--<option>제주</option>-->
                                            <!--</select>-->
                                        <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="pay-cash-col">-->
                                        <!--<label for="pay-info-name2" class="pay-cash-label">입금자명</label>-->
                                        <!--<input type="text" id="pay-info-name2" class="xe-form-control input-170">-->
                                    <!--</div>-->
                                <!--</div> &lt;!&ndash; //pay-cash-row &ndash;&gt;-->
                                <!--<div class="pay-cash-row">-->
                                    <!--<div class="pay-cash-col">-->
                                        <!--<label for="pay-info-account" class="pay-cash-label">계좌번호</label>-->
                                        <!--<input type="text" id="pay-info-account" class="xe-form-control input-426">-->
                                    <!--</div>-->
                                <!--</div> &lt;!&ndash; //pay-cash-row &ndash;&gt;-->

                            <!--</div>-->
                        <!--</div>-->
                    <!--</div>&lt;!&ndash; //pay-type type3 &ndash;&gt;-->

                    <!--&lt;!&ndash; [D] 휴대폰결제  &ndash;&gt;-->
                    <!--<div class="pay-type type4" style="display: none">-->
                        <!--<div class="pay-cash-col">-->
                            <!--<div class="phone-number">-->
                                <!--<div class="xe-select-box xe-btn table-select">-->
                                    <!--<label>010</label>-->
                                    <!--<select>-->
                                        <!--<option>010</option>-->
                                        <!--<option>011</option>-->
                                        <!--<option>017</option>-->
                                        <!--<option>016</option>-->
                                    <!--</select>-->
                                <!--</div>-->
                                <!--<span class="margin-between">-</span>-->
                                <!--<input type="text" class="xe-form-control" maxlength="4">-->
                                <!--<span class="margin-between">-</span>-->
                                <!--<input type="text" class="xe-form-control" maxlength="4">-->
                            <!--</div> &lt;!&ndash; //table-cell-number &ndash;&gt;-->
                        <!--</div>&lt;!&ndash; //pay-cash-col  &ndash;&gt;-->

                    <!--</div>&lt;!&ndash; //pay-type type4 &ndash;&gt;-->

                <!--</div>-->
            <!--</div>&lt;!&ndash; //table-row &ndash;&gt;-->
        </div><!-- //table-type -->
    </div><!-- //table-wrap -->
</template>

<script>
  export default {
    name: "PayComponent",
    props:[
        'payMethods'
    ],
    watch: {
      payMethod (el) {
        this.$emit('input',el)
      }
    },
    data () {
      return {
        methods: [],
        payMethod: null
      }
    },
    mounted () {
      this.methods = this.payMethods
    }
  }
</script>

<style scoped>

</style>
