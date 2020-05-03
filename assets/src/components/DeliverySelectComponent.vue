<template>
    <div class="box-delivery">
        <dl class="list-delivery">
            <dt>배송방법</dt>
            <dd>{{delivery.company.name}}</dd>
        </dl>
        <dl class="list-delivery">
            <dt>배송방법</dt>
            <dd>{{Number(delivery.fare).toLocaleString()}}원</dd>
        </dl>
        <dl class="list-delivery">
            <dt>배송비 결제</dt>
            <dd>
                <label class="xe-label">
                    <input type="radio" checked="checked" value="선불" v-model="pay">
                    <span class="xe-input-helper"></span>
                    <span class="xe-label-text">선불</span>
                </label>
                <label class="xe-label">
                    <input type="radio" value="착불" v-model="pay">
                    <span class="xe-input-helper"></span>
                    <span class="xe-label-text">착불</span>
                </label>
            </dd>
        </dl>
    </div>
</template>

<script>
    export default {
        name: "DeliverySelectComponent",
        watch: {
          pay (el) {
              this.$emit('input', el)
          }
        },
        props: [ 'delivery', 'value'],
        data () {
            return {
                pay: '선불'
            }
        },
        mounted () {
            this.pay = this.value
            this.$emit('input', this.pay)
        }
    }
</script>

<style scoped>

</style>
