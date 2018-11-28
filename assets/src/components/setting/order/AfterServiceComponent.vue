<template>
    <div class="panel">
        <div class="panel-body table-scrollable">
            <table class="table">
                <thead>
                <tr>
                    <th>주문번호</th>
                    <th>항목</th>
                    <th>상세정보</th>
                    <th>고객정보</th>
                    <th>사유</th>
                    <th>처리현황</th>
                    <th>입력완료</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in list">
                    <td>
                        {{item.order_no}}
                    </td>
                    <td>
                        {{item.as.type}}
                    </td>
                    <td>
                        <span v-for="html in item.info" v-html="html"></span>
                    </td>
                    <td>
                        {{item.user.name}} 님 <br>
                        <span>({{item.user.phone}})</span>
                    </td>
                    <td>
                        {{item.as.reason}}
                    </td>
                    <td>
                        제품수령: {{(item.as.received)?'':'미'}}수령 <br>
                        처리완료: {{(item.as.complete)?'처리완료':'처리중'}}
                    </td>
                    <td>
                        <button class="btn btn-default" @click="receive(item.id)">반송품수령</button>
                        <button class="btn btn-default" @click="execute(item.id,item.as.type)">처리완료</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="xero-settings-control-float">
            <div class="pull-left">
                <button class="btn btn-sm btn-link" type="button">엑셀양식다운로드</button>
                <button class="btn btn-sm btn-default" type="button">엑셀업로드</button>
            </div>
            <div class="pull-right">
                <button class="btn btn-primary btn-lg" type="button">입력완료</button>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "AfterServiceComponent",
    props: [
      'list', 'finishUrl', 'receiveUrl'
    ],
    methods: {
      execute(id, type) {
        $.ajax({
          url: this.finishUrl + '/' + type + '/' + id
        }).done(url => {
          var inven = confirm('재고를 수정할까요?')
          if (inven) {
            document.location.href = url
          } else {
            document.location.reload()
          }
        }).fail((err) => {
          console.log(err)
        })
      },
      receive(id) {
        $.ajax({
          url: this.receiveUrl + '/' +id
        }).done(()=>{
          document.location.reload()
        }).fail((err)=>{
          console.log(err)
        })
      }
    },
    mounted() {
      console.log(this.list)
    }
  }
</script>

<style scoped>

</style>
