<template>
    <table class="xe-table">
        <thead>
        <tr>
            <th></th>
            <th>상품정보</th>
            <th>상품금액</th>
            <th>할인금액</th>
            <th>수량</th>
            <th>배송비</th>
            <th>주문금액</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="orderItem in orderItemList">
            <td>
                <img :src="orderItem.src" alt="" width="150px" height="150px">
            </td>
            <td>
                <span v-for="(info,key) in orderItem.info" v-html="info"></span>
            </td>
            <td>
                {{orderItem.original_price.toLocaleString()}}
            </td>
            <td>
                {{orderItem.discount_price.toLocaleString()}}
            </td>
            <td>
                {{orderItem.count}} 개 <br>
            </td>
            <td>
                {{orderItem.delivery_pay}} <br>
                {{orderItem.fare.toLocaleString()}}
            </td>
            <td>
                <b>{{orderItem.sell_price.toLocaleString()}} 원</b> <br>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
  export default {
    name: "OrderItemListComponent",
    props: [
      "orderItemList"
    ],
    data () {
      return {

      }
    }
  }
</script>

<style scoped>

</style>
