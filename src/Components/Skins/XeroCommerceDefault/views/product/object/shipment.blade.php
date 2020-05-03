{{\App\Facades\XeFrontend::js('https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js')->load()}}
<div class="box-shipment" id="shipment">
    <dl class="list-shipment">
        <dt>배송방법</dt>
        <dd>@{{shopCarrier.carrier.name}}</dd>
    </dl>
    <dl class="list-shipment">
        <dt>배송방법</dt>
        <dd>@{{Number(shopCarrier.fare).toLocaleString()}}원</dd>
    </dl>
    <dl class="list-shipment">
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
    <input type="hidden" name="shipment" v-model="pay">
</div>
<script>
    $(function(){
        var shipment = new Vue({
            el:"#shipment",
            name: "ShipmentSelectComponent",
            watch: {
                pay: function (el) {
                    this.$emit('input', el)
                }
            },
            data: function () {
                return {
                    pay: '선불',
                    shopCarrier: {!! json_encode($shopCarrier) !!}
                }
            }
        });
    })
</script>
