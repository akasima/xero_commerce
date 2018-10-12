<template>
    <div class="xe-row">
        <div class="xe-col-lg-12">
            <div style="float:right">
                <button class="xe-btn" type="button">엑셀양식다운로드</button>
                <button class="xe-btn" type="button">엑셀업로드</button>
            </div>
        </div>
        <div class="xe-col-lg-12">
            <table class="xe-table">
                <thead>
                <tr>
                    <th>주문번호</th>
                    <th>항목</th>
                    <th>상세정보</th>
                    <th>고객정보</th>
                    <th>사유</th>
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
                            <button class="btn btn-default" @click="execute(item.id,item.as.type)">완료</button>
                            <button class="btn btn-default">보류</button>
                            <button class="btn btn-default">취소</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="xe-col-lg-12">
            <div style="float:right">
                <button class="xe-btn xe-btn-black" type="button">입력완료</button>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "AfterServiceComponent",
    props: [
        'list', 'finishUrl'
    ],
    methods: {
      execute (id, type) {
        $.ajax({
          url: this.finishUrl + '/' + type + '/' + id
        }).done(url=>{
          var inven = confirm('재고를 수정할까요?')
          if (inven) {
            document.location.href=url
          }else{
            document.location.reload()
          }
        }).fail((err)=>{
          console.log(err)
        })
      }
    },
    mounted () {
      console.log(this.list)
    }
  }
</script>

<style scoped>

</style>