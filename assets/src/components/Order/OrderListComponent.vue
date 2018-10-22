<template>
    <div>
        <div class="xe-row mt-5">
            <div class="xe-col-lg-3">
                <div class="xe-btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="xe-btn xe-btn-default" @click="setDate(0,'days')">오늘</button>
                    <button type="button" class="xe-btn xe-btn-default" @click="setDate(1,'weeks')">1주일</button>
                    <button type="button" class="xe-btn xe-btn-default" @click="setDate(1,'months')">1개월</button>
                    <button type="button" class="xe-btn xe-btn-default" @click="setDate(6,'months')">6개월</button>
                </div>
            </div>
            <div class="xe-col-lg-3">
                <input type="date" v-model="date[0]" class="form-control">
                <input type="date" v-model="date[1]" class="form-control">
            </div>
            <div class="xe-col-lg-3">
                <select class="form-control" v-model="status">
                    <option value="all">전부</option>
                    <option v-for="(name, value) in statusList" :value="value">{{name}}</option>
                </select>
            </div>
            <div class="xe-col-lg-3">
                <button class="xe-btn xe-btn-block xe-btn-black" :disabled="btn" @click="load">조회</button>
            </div>
        </div>
        <div class="xe-row">
            <div class="xe-col">
                <order-table
                        :list="tableList"
                        :paginate="tablePaginate"
                        @page="pagination"
                        :as-url="asUrl"
                        :detail-url="detailUrl"
                ></order-table>
            </div>
        </div>
    </div>
</template>

<script>
  import OrderTable from './OrderTable'
  import moment from 'moment'

  export default {
    name: "OrderListComponent",
    components: {
      OrderTable
    },
    props: [
      'list', 'loadUrl', 'statusList', 'token', 'default', 'paginate', 'asUrl', 'detailUrl'
    ],
    data() {
      return {
        tableList: [],
        tablePaginate: {
          current_page:1,
          last_page:1,
          first_page:1,
          total:0
        },
        page: 1,
        count: 5,
        date: this.default.date,
        status: this.default.equal.code,
        btn:false
      }
    },
    methods: {
      load() {
        this.btn=true
        $.ajax({
          url: this.loadUrl + '/' + this.page,
          method: 'post',
          data: {
            condition: {
              date: this.date,
              equal: {
                code: this.status
              },
              compare:{

              },
              inGroup:{

              }
            },
            count: this.count,
            _token: this.token
          },
        }).done(res => {
          this.tableList = res.data
          this.tablePaginate = res.paginate
          console.log(res)
          this.btn=false
        }).fail(() => {
          this.btn=false
        })
      },
      setDate(count, unit) {
        this.date = [
          moment().subtract(count, unit).format('YYYY-MM-DD'),
          moment().format('YYYY-MM-DD')
        ]
      },
      pagination (page) {
        this.page = page
        this.load()
      }
    },
    mounted() {
      console.log(this.list)
      console.log(this.paginate)
      this.tableList = this.list
      this.tablePaginate = this.paginate

    }
  }
</script>

<style scoped>

</style>
