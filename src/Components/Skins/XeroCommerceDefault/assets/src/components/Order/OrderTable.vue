<template>
    <table class="xe-table">
        <thead>
        <tr>
            <th width="170">주문일자</th>
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
                <td style="text-align: center; background:#f1f1f1;cursor:pointer" :rowspan="item.orderItems.length" v-if="key===0" @click="url('/shopping/order/detail/'+item.id)">
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
</template>

<script>
  export default {
    name: "OrderTable",
    props: [
        'list'
    ],
    methods: {
      url (url) {
        document.location.href=url
      }
    }
  }
</script>

<style scoped>

</style>