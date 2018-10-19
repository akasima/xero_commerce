<template>
    <table class="xe-table">
        <thead>
        <tr>
            <th>옵션 타입</th>
            <th>옵션명</th>
            <th>추가 금액</th>
            <th>실 구매 금액</th>
            <th>현재 재고</th>
            <th>품절 알림 재고</th>
            <th>출력 상태</th>
            <th>판매 상태</th>
            <th>관리</th>
        </tr>
        </thead>

        <tbody>
            <row-component v-for="(option,key) in options" v-bind:optionData="option" :key="key"></row-component>
        </tbody>
    </table>
</template>

<script>
    import RowComponent from './RowComponent'

    export default {
        name: "OptionTableComponent",
        components: {
            RowComponent
        },
        props: [
            'options'
        ]
    }
</script>

<style scoped>

</style>
