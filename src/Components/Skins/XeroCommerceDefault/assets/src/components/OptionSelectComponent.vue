<template>
    <div class="row">
        <div class="col-sm-12">
            <slot></slot>
            <table class="table">
                <tr v-if="!onlyOneOption">
                    <th>옵션</th>
                    <td>
                        <select v-model="selectOption" style="width:100%">
                            <option :value="null">선택</option>
                            <option v-for="option in options" :value="option">{{option.name}}
                                (+{{Number(option.add_price).toLocaleString()}} )
                            </option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-sm-12">
            <div class="row" v-for="(selectedOption, key) in select">
                <div class="col-sm-5 col-sm-offset-1">
                    <span v-if="!onlyOneOption">선택: {{selectedOption.unit.name}} ({{selectedOption.unit.sell_price.toLocaleString()}})</span>
                </div>
                <div class="col-sm-5">
                    <input type="number" v-model="selectedOption.count"> 개
                </div>
                <div class="col-sm-1" v-if="!onlyOneOption">
                    <i class="xi-close" @click="dropOption(key)"></i>
                </div>
            </div>
        </div>
        <div class="col-sm-12 text-right">
            총상품금액
            <h2>{{totalChoosePrice.toLocaleString()}} 원 </h2>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OptionSelectComponent",
        watch: {
            selectOption(el) {
                if (el == null) return
                var exist = this.select.find(v => {
                    return v.unit.id === el.id
                })
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
                this.selectOption = null
            }
        },
        computed: {
            totalChoosePrice() {
                return this.sum(this.select)
            },
            onlyOneOption () {
                return this.options.length ===1
            }
        },
        props: [
            'options', 'alreadyChoose'
        ],
        data() {
            return {
                selectOption: null,
                'select': [],
                pay: 'prepay'
            }
        },
        methods: {
            sum(array) {
                return array.map((v) => {
                    return v.unit.sell_price * v.count
                }).reduce((a, b) => a + b, 0);
            },
            dropOption(key) {
                this.select.splice(key, 1)
            },
        },
        mounted() {
            this.select = this.alreadyChoose
            this.$emit('input', this.select)
            if(this.onlyOneOption) this.selectOption=this.options[0]
        }
    }
</script>

<style scoped>

</style>
