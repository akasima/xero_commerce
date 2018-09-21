<template>
    <div>
        <table class="xe-table">
            <tbody>
            <tr>
                <th rowspan="2">장바구니결제정보</th>
                <td>상품금액</td>
                <td>할인금액</td>
                <td>배송금액</td>
                <td class="text-left">결제 예정 금액</td>
            </tr>
            <tr>
                <td>{{Number(summary.original_price).toLocaleString()}} 원</td>
                <td>{{Number(summary.discount_price).toLocaleString()}} 원</td>
                <td>{{Number(summary.fare).toLocaleString()}} 원 <br>
                    <p>적립금 혜택 {{Number(millage).toLocaleString()}}원</p>
                </td>
                <td>{{Number(summary.sum).toLocaleString()}} 원</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
  export default {
    name: "CartSumComponent",
    props: [
      'summary'
    ],
    data () {
      return {
        millage: 0
      }
    }
  }
</script>

<style scoped>

</style>