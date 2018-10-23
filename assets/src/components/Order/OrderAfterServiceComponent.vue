<template>
    <div class="row">
        <div class="xe-col-lg-12">
            <table class="xe-table">
                <thead>
                <tr>
                    <th width="170">주문일자</th>
                    <th>상품정보</th>
                    <th>결제금액</th>
                    <th>수량</th>
                    <th>배송비</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="text-align: center; background:#f1f1f1;cursor:pointer" :rowspan="3">
                        <p>{{item.order_no}}</p>
                        <p style="color:#aaa">{{order.created_at.substr(0,16)}}</p>
                    </td>
                    <td style="cursor: pointer;">
                        <div class="row">
                            <div class="col">
                                <img :src="item.src" alt="" width="120" height="120">
                            </div>
                            <div class="col">
                                <span v-for="html in item.info" v-html="html"></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        {{item.sell_price.toLocaleString()}}
                    </td>
                    <td>
                        {{item.count}} 개
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="row">
                            <div class="col-lg-2">
                                {{type}}사유
                            </div>
                            <div class="col-lg-10">
                                <textarea rows="10" style="width:100%; resize:none" v-model="reason"></textarea>
                            </div>
                        </div>
                    </td>
                    <td colspan="2">
                        <div class="form-group">
                            <label for="">택배사</label>
                            <select v-model="delivery" class="form-control">
                                <option v-for="com in company" :value="com.id">{{com.name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">송장번호</label>
                            <input v-model="ship_no" type="text" class="form-control"/>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div class="row">
                            <div class="col-lg-6">
                                결제
                                <pay-component :pay-methods="payMethods" v-model="payMethod" @pay="execute"></pay-component>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h2>최종 결제금액</h2>
                                    </div>
                                    <div class="card-content">
                                        <table class="xe-table">
                                            <tr>
                                                <th>{{type}}비용</th>
                                                <td>0 원</td>
                                            </tr>
                                            <tr>
                                                <th>적립금 사용</th>
                                                <td><i class="xi-minus-min"></i> 0 원</td>
                                            </tr>
                                            <tr>
                                                <th>배송비</th>
                                                <td><i class="xi-plus-min"></i> 0 원</td>
                                            </tr>
                                            <tr>
                                                <th>최종 결제금액</th>
                                                <td>0 원</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <hr>
        <div style="text-align: center" class="xe-col-lg-2 xe-col-lg-offset-4">
            <button class="xe-btn xe-btn-black xe-btn-lg xe-btn-block" @click="execute">{{type}}하기</button>
        </div>
        <div style="text-align: center" class="xe-col-lg-2">
            <button class="xe-btn xe-btn-lg xe-btn-block" type="button">이전으로</button>
        </div>
    </div>
</template>

<script>
  import PayComponent from './PayComponent'
  import OrderBillComponent from './OrderBillComponent'

  export default {
    name: "OrderAfterServiceComponent",
    components: {
      PayComponent, OrderBillComponent
    },
    props: [
      'order', 'item', 'type', 'payMethods', 'company', 'executeUrl', 'token', 'successUrl'
    ],
    computed: {
      validate() {
        var res = {
          status: true,
          msg: ''
        }
        return res
      }
    },
    data() {
      return {
        payMethod: null,
        reason: '',
        delivery: null,
        ship_no:''
      }
    },
    methods: {
      execute () {
        $.ajax({
          url: this.executeUrl+'/'+this.type+'/'+this.item.id,
          method:'post',
          data: {
            reason: this.reason,
            delivery: this.delivery,
            ship_no: this.ship_no,
            _token: this.token
          }
        }).done(()=>{
          document.location.href=this.successUrl
        }).fail(()=>{
          console.log('fail')
        })
      }
    },
    mounted() {
      console.log(this.payMethods)
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