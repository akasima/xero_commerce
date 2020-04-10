{{\App\Facades\XeFrontend::js('https://cdn.jsdelivr.net/npm/vue/dist/vue.min.js')->load()}}
<div id="option">
    {{-- 기본값(옵션품목1개), 조합일체형 --}}
    @if($optionType == \Xpressengine\Plugins\XeroCommerce\Models\Product::OPTION_TYPE_COMBINATION_MERGE)
    <div class="box-option">
        <strong>선택항목</strong>
        <select v-model="selectedOptionItem" class="form-select">
            <option disabled="" selected value="null">옵션을 선택해주세요</option>
            <option v-for="item in optionItems" :value="item" :disabled="item.state_deal!=='판매중'">@{{item.name}}
                (+@{{Number(item.addition_price).toLocaleString()}} ) @{{(item.state_deal!=='판매중')? '-'+ item.state_deal: ''}}
            </option>
        </select>
    </div>
    @endif
    {{--  조합분리형 & 단독형  --}}
    @if($optionType == \Xpressengine\Plugins\XeroCommerce\Models\Product::OPTION_TYPE_COMBINATION_SPLIT || $optionType == \Xpressengine\Plugins\XeroCommerce\Models\Product::OPTION_TYPE_SIMPLE)
    <div v-for="(option, i) in options" class="box-option">
        <strong>@{{ option.name }}</strong>
        <select v-model="selectedOptions[i]" class="form-select">
            <option disabled="" selected value="undefined">[필수] 옵션을 선택해주세요</option>
            <option v-for="value in option.values" :value="{[option.name] : value}">@{{value}}</option>
        </select>
    </div>
    @endif
    <div class="product-info-counter">
        <div v-if="!hasOnlyOneItem" class="product-info-cell" v-for="(selectedOption, key) in select">
            <div class="product-info-counter-title">@{{selectedOption.unit.name}} </div>
            <div class="xe-spin-box">
                <button type="button" @click="selectedOption.count--; if(selectedOption.count<=0)dropOption(key)"><i class="xi-minus-thin"></i><span class="xe-sr-only">감소</span></button>
                <p>@{{selectedOption.count}}</p>
                <button type="button" @click="selectedOption.count++"><i class="xi-plus-thin"></i><span class="xe-sr-only">증가</span></button>
            </div>
            <p class="product-info-counter-sum">@{{(selectedOption.unit.sell_price * selectedOption.count).toLocaleString()}}원</p>
            <button class="xe-btn xe-btn-remove" @click="dropOption(key)"><i class="xi-close-thin"></i><span class="xe-sr-only">이 옵션 삭제</span></button>
        </div> <!-- //product-info-cell -->

        <div v-if="hasOnlyOneItem" class="product-info-cell" v-for="(selectedOption, key) in select">
            <div class="product-info-counter-title">@{{selectedOption.unit.name}} </div>
            <div class="xe-spin-box">
                <button type="button" @click="(selectedOption.count>1) ? selectedOption.count-- : ''"><i class="xi-minus-thin"></i><span class="xe-sr-only">감소</span></button>
                <p>@{{selectedOption.count}}</p>
                <button type="button" @click="selectedOption.count++"><i class="xi-plus-thin"></i><span class="xe-sr-only">증가</span></button>
            </div>
            <p class="product-info-counter-sum">@{{(selectedOption.unit.sell_price * selectedOption.count).toLocaleString()}}원</p>
        </div>
    </div> <!-- //product-info-counter -->

    <p class="price-sum"><span class="text">총 상품금액</span> @{{totalChoosePrice.toLocaleString()}}<i>원</i></p>
    <input type="hidden" v-model="chooseJson" name="choose">
</div>
<script>
    $(function(){
        new Vue({
            el: "#option",
            name: "OptionSelectComponent",
            watch: {
                // 조합분리형 & 단독형을 위한 함수
                selectedOptions(selectedOptions) {
                    // 모든 옵션이 선택되었다면
                    if(selectedOptions.length == this.options.length) {
                        // 옵션품목중 일치하는 조건으로 가져옴
                        let optionItem = this.optionItems.find(item => {
                            let selectedCombination = selectedOptions.reduce((obj, item) => ({...obj, ...item}), {});
                            return JSON.stringify(item.value_combination) == JSON.stringify(selectedCombination);
                        });
                        // 일치하는 품목이 있으면
                        if(optionItem) {
                            this.addOptionItemToList(optionItem);
                        }
                    }
                },
                selectedOptionItem (el) {
                    return this.addOptionItemToList(el);
                },
                reset: function () {
                    this.select= [];
                    this.initialize();
                }
            },
            computed: {
                totalChoosePrice: function() {
                    return this.sum(this.select)
                },
                hasOnlyOneItem: function () {
                    return this.optionItems.length ===1
                },
                chooseJson: function () {
                    return JSON.stringify(this.select)
                }
            },
            data: function() {
                return {
                    selectedOptions: [],
                    selectedOptionItem: null,
                    'select': [],
                    pay: 'prepay',
                    options: {!! json_encode($options) !!},
                    optionItems: {!! json_encode($optionItems) !!},
                    alreadyChoose:{!! json_encode($choose) !!},
                    reset:null
                }
            },
            methods: {
                sum: function (array) {
                    return array.map(function (v) {
                        return v.unit.sell_price * v.count
                    }).reduce(function (a, b) { return a + b }, 0);
                },
                dropOption: function (key) {
                    this.select.splice(key, 1)
                },
                toggleButton: function () {
                    if($("#toggleBtn").hasClass("on")){
                        $("#toggleBtn").removeClass("on")
                    }else{
                        $("#toggleBtn").addClass("on")
                    }
                },
                initialize: function () {
                    if(this.hasOnlyOneItem && this.select.length===0) this.selectedOptionItem=this.optionItems[0];
                },
                // 선택된 옵션목록에 아이템 추가
                addOptionItemToList(el) {
                    if (el == null) return
                    let exist = this.select.find(function(v){
                        return v.unit.id === el.id
                    });
                    if (exist) {
                        exist.count++
                    } else {
                        this.select.push({
                            id: null,
                            unit: el,
                            count: 1
                        })
                    }
                    this.$emit('input', this.select)
                    this.selectedOptionItem = null;
                    this.selectedOptions = [];
                }
            },
            mounted: function () {
                this.select = this.alreadyChoose
                this.$emit('input', this.select)
                this.initialize()
            }
        })
    })
</script>
