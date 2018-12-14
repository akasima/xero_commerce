{{\App\Facades\XeFrontend::js('https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js')->load()}}
<div class="box-delivery" id="delivery">
    <dl class="list-delivery">
        <dt>배송방법</dt>
        <dd>@{{delivery.company.name}}</dd>
    </dl>
    <dl class="list-delivery">
        <dt>배송방법</dt>
        <dd>@{{Number(delivery.delivery_fare).toLocaleString()}}원</dd>
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
    <input type="hidden" name="delivery" v-model="pay">
</div>
<script>
    $(function(){
        var delivery = new Vue({
            el:"#delivery",
            name: "DeliverySelectComponent",
            watch: {
                pay (el) {
                    this.$emit('input', el)
                }
            },
            data () {
                return {
                    pay: '선불',
                    delivery: {!! json_encode($delivery) !!}
                }
            },
            mounted () {
                this.$emit('input', this.pay)
            }
        });
    })
</script>
