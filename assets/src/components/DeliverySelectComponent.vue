<template>
    <table class="table">
        <tr>
            <th>배송방법</th>
            <td>{{delivery.name}}</td>
        </tr>
        <tr>
            <th>배송비</th>
            <td>{{Number(delivery.pivot.delivery_fare).toLocaleString()}} </td>
        </tr>
        <tr>
            <th>배송비 결제</th>
            <td>
                <b-form-group>
                    <b-form-checkbox-group id="checkboxes1" v-model="pay">
                        <b-form-checkbox value="선불">선불</b-form-checkbox>
                        <b-form-checkbox value="착불">착불</b-form-checkbox>
                    </b-form-checkbox-group>
                </b-form-group>
            </td>
        </tr>
    </table>
</template>

<script>
    export default {
        name: "DeliverySelectComponent",
        watch: {
          pay (el) {
              this.$emit('input', el)
              console.log(el)
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
