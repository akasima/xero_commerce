<template>
    <table class="table">
        <tr>
            <th>배송방법</th>
            <td>{{delivery.company.name}}</td>
        </tr>
        <tr>
            <th>배송비</th>
            <td>{{Number(delivery.delivery_fare).toLocaleString()}} </td>
        </tr>
        <tr>
            <th>배송비 결제</th>
            <td>
                <div class="table-cell">
                    <label>
                        <input type="radio" checked="checked" name="pay-type" value="선불" v-model="pay">
                        <span class="xe-input-helper"></span>
                        <span class="xe-label-text">선불</span>
                    </label>
                    <label>
                        <input type="radio" checked="checked" name="pay-type" value="착불" v-model="pay">
                        <span class="xe-input-helper"></span>
                        <span class="xe-label-text">착불</span>
                    </label>
                </div>
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
            console.log(this.delivery)
        }
    }
</script>

<style scoped>

</style>
