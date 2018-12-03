<template>
    <div class="payment-aside-agree">
        <h1 class="xe-sr-only">동의</h1>

        <div class="payment-aside-agree-all xe-form-inline">
            <label class="xe-label">
                <input type="checkbox" v-model="allCheck">
                <span class="xe-input-helper"></span>
                <span class="xe-label-text agree-all-text">전체 동의합니다.</span>
            </label>
        </div><!-- //payment-aside-agree-all -->

        <div class="payment-aside-agree-article" v-for="(agreement, key) in agreements" :key="key">
            <div class="payment-aside-agree-header xe-form-inline">
                <label class="xe-label">
                    <input type="checkbox" v-model="checked" :value="agreement">
                    <span class="xe-input-helper"></span>
                    <span class="xe-label-text">{{agreement.name}}</span>
                </label>
                <button type="button" class="btn-toggle" @click="opened[agreement.type]=!opened[agreement.type]"><i :class="(opened[agreement.type])?'xi-angle-up-thin':'xi-angle-down-thin'"></i></button>
            </div><!-- //payment-aside-agree-header -->
            <div class="payment-aside-agree-content color" v-show="opened[agreement.type]" v-html="makeBr(agreement.contents)">
            </div><!-- //payment-aside-agree-content -->
        </div><!-- //payment-aside-agree -->
    </div><!-- //payment-aside-agree -->
</template>

<script>
    export default {
        name: "OrderAgreementComponent",
        props: [
            'agreements', 'agreeUrl', 'deniedUrl'
        ],
        watch: {
            checked(el, oldEl) {
                this.$emit('input', this.checked)
                var regDiff = $(el).not(oldEl).get()
                if(regDiff.length>0){
                    $.each(regDiff,(k,v)=>{
                        this.register(v)
                    })
                }
                if(oldEl){
                    var remDiff = $(oldEl).not(el).get()
                    $.each(remDiff,(k,v)=>{
                        this.remove(v)
                    })
                }
            },
            allCheck (el) {
                if(el){
                    this.checked = Object.values(this.agreements)
                }else{
                    this.checked = []
                }
            }
        },
        data() {
            return {
                checked: [],
                opened: {
                    purchase: true,
                    privacy: false,
                    thirdParty: false
                },
                allCheck:false
            }
        },
        methods: {
            toggling(agreement) {
                this.opened.purchase = false
                this.opened.privacy = false
                this.opened.thirdParty = false
                this.opened[agreement.type] = !this.opened[agreement.type]
            },
            register (agreement) {
                $.ajax({
                    url: this.agreeUrl,
                    method:'post',
                    data: {
                        id: agreement.id,
                        _token: document.getElementById('csrf_token').value
                    }
                })
            },
            remove (agreement) {
                $.ajax({
                    url: this.deniedUrl,
                    method: 'post',
                    data: {
                        id: agreement.id,
                        _token: document.getElementById('csrf_token').value
                    }
                })
            },
            makeBr (string) {
                return string.replace(/(?:\r\n|\r|\n)/g, '<br>');
            }
        }
    }
</script>

<style scoped>

</style>
