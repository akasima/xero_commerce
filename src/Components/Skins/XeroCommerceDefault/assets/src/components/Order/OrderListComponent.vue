<template>
    <div>
        <div class="xe-row mt-5">
            <div class="xe-col-lg-3">
                <div class="xe-btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="xe-btn xe-btn-default">오늘</button>
                    <button type="button" class="xe-btn xe-btn-default">1주일</button>
                    <button type="button" class="xe-btn xe-btn-default">1개월</button>
                    <button type="button" class="xe-btn xe-btn-default">6개월</button>
                </div>
            </div>
            <div class="xe-col-lg-3">
                <input type="date" class="form-control">
                <input type="date" class="form-control">
            </div>
            <div class="xe-col-lg-3">
                <select class="form-control">
                    <option value="">대기</option>
                </select>
            </div>
            <div class="xe-col-lg-3">
                <button class="xe-btn xe-btn-block xe-btn-black">조회</button>
            </div>
        </div>
        <div class="xe-row">
            <div class="xe-col">
                <table class="xe-table">
                    <thead>
                    <tr>
                        <th>주문일자</th>
                        <th>상품정보</th>
                        <th>결제금액</th>
                        <th>수량</th>
                        <th>배송비</th>
                        <th>주문상태</th>
                    </tr>
                    </thead>
                    <tbody>
                        <template v-for="item in list">
                            <tr v-for="(orderitem, key) in item.orderItems">
                                <td style="text-align: center" :rowspan="item.orderItems.length" v-if="key===0">
                                    <p>{{item.order_no}}</p>
                                    <p style="color:#aaa">{{item.created_at.substr(0,16)}}</p>
                                    <h3>{{item.status}}</h3>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col">
                                            <img :src="orderitem.src" alt="" width="120" height="120">
                                        </div>
                                        <div class="col">
                                            <span v-for="html in orderitem.info" v-html="html"></span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{orderitem.sell_price.toLocaleString()}}
                                </td>
                                <td>
                                    {{orderitem.count}} 개
                                </td>
                                <td></td>
                                <td style="text-align: center;">
                                    <p>{{orderitem.status}}</p>
                                    <p><button class="xe-btn xe-btn-default">배송교회</button></p>
                                    <p><button class="xe-btn xe-btn-default">교환요청</button></p>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "OrderListComponent",
    props: [
      'list'
    ],
    mounted () {
      console.log(this.list)
    }
  }
</script>

<style scoped>

</style>