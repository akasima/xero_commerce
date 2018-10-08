<template>
    <div>
        <h2>상품내역</h2>
        <order-table
        :list="[order]"></order-table>
        <h2>결제금액정보</h2>
        <table class="table">
            <tr>
                <th>결제 금액</th>
                <td>{{order.payment.price.toLocaleString()}}</td>
            </tr>
            <tr>
                <th>결제 수단</th>
                <td>{{order.payment.method}}</td>
            </tr>
            <tr>
                <th>결제 완료일시</th>
                <td></td>
            </tr>
        </table>
        <h2>주문정보</h2>
        <table class="table">
            <tr>
                <th>이름</th>
                <td>{{order.user_info.name}}</td>
            </tr>
            <tr>
                <th>연락처</th>
                <td>{{order.user_info.phone}}</td>
            </tr>
        </table>
        <h2>배송정보</h2>
        <table class="table">
            <tr>
                <th>받는 사람</th>
                <td>{{order.order_items[0].delivery.recv_name}}</td>
            </tr>
            <tr>
                <th>연락처</th>
                <td>{{order.order_items[0].delivery.recv_phone}}</td>
            </tr>
            <tr>
                <th>주소</th>
                <td>{{order.order_items[0].delivery.recv_addr + order.order_items[0].delivery.recv_addr_detail}}</td>
            </tr>
            <tr>
                <th>배송메세지</th>
                <td>{{order.order_items[0].delivery.recv_msg}}</td>
            </tr>
        </table>
    </div>
</template>

<script>
    import OrderTable from './OrderTable'
  export default {
    name: "OrderDetailComponent",
    props:[
        'order'
    ],
    components: {
      OrderTable
    },
    mounted () {
      console.log(this.order)
    }
  }
</script>

<style scoped>
.table th {
    background:#f1f1f1;
    width:200px;
}
</style>