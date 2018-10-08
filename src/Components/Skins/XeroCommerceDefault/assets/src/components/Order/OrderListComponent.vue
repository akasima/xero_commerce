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
                <input type="date" class="form-control">
                <input type="date" class="form-control">
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
                :list="list"></order-table>
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
    ]
  }
</script>

<style scoped>

</style>