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
                <input type="date" v-model="date[0]" class="form-control">
                <input type="date" v-model="date[1]" class="form-control">
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
                <order-table
                :list="tableList"></order-table>
            </div>
        </div>
    </div>
</template>

<script>
    import OrderTable from './OrderTable'
  export default {
    name: "OrderListComponent",
    components: {
      OrderTable
    },
    props: [
      'list'
    ],
    data () {
      return {
        tableList: [],
        page: 1,
        count: 10,
        date: [
            '2018-05-01',
            new Date().toISOString().substr(0,10)
        ],
        status: null
      }
    },
    methods: {
        load () {
          $.ajax({
            url:d,
            data:{
              date: d,
              status: d,
              page: this.page,
              count: this.count
            },
          }).done(()=>{

          }).fail(()=>{

          })
        }
    },
    mounted () {
      this.tableList = this.list
    }
  }
</script>

<style scoped>

</style>