<template>
    <div>
        <section class="shipping-search">
            <h2 class="xe-sr-only">검색 영역</h2>
            <div class="shipping-search-btn">
                <h3 class="xe-sr-only">주 또는 개월 검색 버튼</h3>
                <div class="xe-btn-group">
                    <button type="button" class="xe-btn" @click="setDate(0,'days')">오늘</button>
                    <button type="button" class="xe-btn" @click="setDate(1,'weeks')">1주일</button>
                    <button type="button" class="xe-btn" @click="setDate(1,'months')">1개월</button>
                    <button type="button" class="xe-btn" @click="setDate(6,'months')">6개월</button>
                </div>
            </div><!-- //shipping-search-btn -->

            <div class="shipping-search-term">
                <h3 class="xe-sr-only">일자 검색</h3>
                <div class="shipping-search-term-inner">
                    <div class="xe-input-group">
                        <input type="text" class="xe-form-control" placeholder="0000-00-00" v-model="date[0]">
                        <span class="xe-input-group-btn">
                  <button class="xe-btn " type="button"><span class="xe-sr-only">검색 시작일</span><i class="xi-calendar"></i></button>
                </span>
                    </div>
                </div>
                <span class="shipping-search-term-between">~</span>
                <div class="shipping-search-term-inner">
                    <div class="xe-input-group">
                        <input type="text" class="xe-form-control" placeholder="0000-00-00" v-model="date[1]">
                        <span class="xe-input-group-btn">
                  <button class="xe-btn " type="button"><span class="xe-sr-only">검색 종료일</span><i class="xi-calendar"></i></button>
                </span>
                    </div>
                </div>
            </div><!-- //shipping-search-term -->

            <div class="shipping-search-status">
                <h3 class="xe-sr-only">상태별 검색 </h3>
                <div class="xe-select-box xe-btn">
                    <label>{{(statusList[status]) ? statusList[status] :'전부'}}</label>
                    <select v-model="status">
                        <option value="all">전부</option>
                        <option v-for="(name, value) in statusList" :value="value">{{name}}</option>
                    </select>
                </div>
                <button class="xe-btn shipping-search-confirm" :disabled="btn" @click="load">조회</button>
            </div> <!-- //shipping-search-status -->

        </section><!-- //shipping-search -->
        <div class="xe-row">
            <div class="xe-col">
                <order-table
                        :list="tableList"
                        :paginate="tablePaginate"
                        @page="pagination"
                        :as-url="asUrl"
                        :cancel-url="cancelUrl"
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
      'list', 'loadUrl', 'statusList', 'token', 'default', 'paginate', 'asUrl', 'detailUrl', 'cancelUrl'
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
