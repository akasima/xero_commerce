<template>
    <div class="panel">
        <div class="panel-body">
            <div class="table-scrollable">
                <table class="table">
                    <thead>
                    <tr>
                        <th><input type="checkbox" v-model="allCheck"></th>
                        <th>주문번호</th>
                        <th>상세정보</th>
                        <th>주소</th>
                        <th>배송사</th>
                        <th>송장번호</th>
                        <th>입력완료</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="order_item in orderItems">
                        <td>
                            <input v-model="checked" type="checkbox" :value="order_item.id">
                        </td>
                        <td>
                            {{order_item.order_no}}
                            <p style="text-align: center; font-weight: bold;">[{{order_item.status}}]</p>
                        </td>
                        <td>
                            <span v-for="html in order_item.info" v-html="html"></span>
                        </td>
                        <td>
                            {{order_item.delivery.recv_addr + ' ' + order_item.delivery.recv_addr_detail}} <br>
                            수령인 : {{order_item.delivery.recv_name}}
                        </td>
                        <td>
                            {{order_item.delivery.company.name}}
                        </td>
                        <td>
                            <span v-if="order_item.delivery.ship_no !==''">{{order_item.delivery.ship_no}}</span>
                            <input v-if="order_item.delivery.ship_no ===''" type="text" v-model="texted[order_item.id]">
                        </td>
                        <td>
                            <button v-if="order_item.delivery.ship_no ===''" @click="select(order_item.id)">배송중</button>
                            <button v-if="order_item.status !=='완료'" @click="selectComplete(order_item.id)">배송완료</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer text-right">
            <input type="text" v-model="allNo">
            <button class="btn btn-black" type="button" @click="submit">일괄 배송중</button>
            <button class="btn btn-black" type="button" @click="complete">일괄 배송완료</button>
        </div>
        <div class="xero-settings-control-float">
            <button class="btn btn-default" type="button">엑셀양식다운로드</button>
            <button class="btn btn-default" type="button">엑셀업로드</button>
        </div>
    </div>
</template>

<script>
  export default {
    name: "DeliveryComponent",
    props: [
        'orderItems', 'token'
    ],
    watch: {
      allNo(el){
        console.log(el)
        this.checked.forEach(v=>{
          this.texted[v] = el
        })
      },
      allCheck (el) {
        this.checked=(el)?this.orderItems.map(v=>{return v.id}):[];
      }
    },
    computed:{
      delivery () {
        return this.checked.map(v=>{
          return {
            id: v,
            no: this.texted[v]
          }
        })
      }
    },
    data() {
      return {
        checked:[],
        texted:[],
        allNo:'370269846894',
        allCheck: false
      }
    },
    mounted () {
      console.log(this.orderItems)
    },
    methods: {
      select (id) {
        this.checked = [id]
        this.submit()
      },
      selectComplete (id) {
        this.checked = [id]
        this.complete()
      },
      submit () {
          this.texted=[]
          $.each(this.checked,(k,v)=>{
              console.log(this.checked)
              console.log(v)
              this.texted[v] = this.allNo
          })
        if(this.validate()){
          $.ajax({
            url:'/settings/xero_commerce/order/delivery',
            method:'post',
            data:{
              delivery: this.delivery,
              _token: this.token
            }
          }).done(()=>{
            document.location.reload()
          }).fail((err)=>{
            console.log(err)
          })
        }
      },
      validate () {
        var err=[];
        this.delivery.forEach(v=>{
          if(typeof v.no === 'undefined' || String(v.no) === '') {
            err.push(v)
          }
        })
        if(err.length>0){
          alert('송장번호 입력이 안된 주문 또는 이미 배송중인 주문이 있습니다.')
          return false;
        }else{
          return true;
        }
      },
      complete () {
        $.ajax({
          url:'/settings/xero_commerce/order/delivery/complete',
          method:'post',
          data:{
            delivery: this.checked,
            _token: this.token
          }
        }).done(()=>{
          document.location.reload()
        }).fail((err)=>{
          console.log(err)
        })
      }
    }
  }
</script>

<style scoped>

</style>
