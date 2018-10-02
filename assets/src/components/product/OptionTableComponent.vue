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
            <tr v-for="option in options">
                <td>{{ option.option_type }}</td>
                <td>{{ option.name}}</td>
                <td>{{ option.addition_price }}</td>
                <td></td>
                <td>{{ option.stock }}</td>
                <td>{{ option.alert_stock }}</td>
                <td>{{ option.state_display }}</td>
                <td>{{ option.state_deal }}</td>
                <td>
                    <button type="button" class="xe-btn xe-btn-positive">수정</button>
                </td>
            </tr>

            <edit-row-component></edit-row-component>
        </tbody>
    </table>
</template>

<script>
    import ShowRowComponent from './ShowRowComponent'
    import EditRowComponent from './EditRowComponent'

    export default {
        name: "OptionTableComponent",
        components: {
            ShowRowComponent, EditRowComponent
        },
        props: [
            'options'
        ],
        methods: {

        },
        mounted() {
            console.log(this.options);
        }
    }
</script>

<style scoped>

</style>
