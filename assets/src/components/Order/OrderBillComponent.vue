<template>
    <div class="card">
        <div class="card-header">
            <h2>최종 결제금액</h2>
        </div>
        <div class="card-content">
            <table class="xe-table">
                <tr>
                    <th>상품금액</th>
                    <td>{{Number(summary.original_price).toLocaleString()}} 원</td>
                </tr>
                <tr>
                    <th>할인금액</th>
                    <td> <i class="xi-minus-min"></i> {{Number(summary.discount_price).toLocaleString()}} 원</td>
                </tr>
                <tr v-if="discountOption">
                    <th>적립금 사용</th>
                    <td><i class="xi-minus-min"></i> 0 원</td>
                </tr>
                <tr>
                    <th>배송비</th>
                    <td><i class="xi-plus-min"></i> {{Number(summary.fare).toLocaleString()}} 원</td>
                </tr>
                <tr>
                    <th>최종 결제금액</th>
                    <td>{{Number(summary.sum).toLocaleString()}} 원</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="button" class="xe-btn xe-btn-lg xe-btn-black xe-btn-block" @click="pay">결제하기</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
  export default {
    name: "OrderBillComponent",
    props: [
        'summary', 'payOption', 'validate', 'method', 'discountOption'
    ],
    methods: {
      pay () {
        if(!this.validate.status) {
          alert(this.validate.msg)
          return false
        }
        payment.submit({
          methods: this.method
        },res=>{
          this.$emit('pay', res)
        }, err => {
          alert('결제 실패!!' + err)
        })
      }
    }
  }
</script>

<style scoped>
    .card-content {
        padding: 10px;
    }
    .card-content tr {
        border-bottom: 1px #eee solid;
    }
    .card-content th, .card-content td {
        text-align: right;
        font-size:13pt;
    }
    .card-content th {
        width:30%;
        text-align: left;
    }
</style>
