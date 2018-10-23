<template>
    <div>
        <div role="tablist">
            <b-card v-for="(agreement, key) in agreements" :key="key">
                <b-card-header role="tab">
                    <b-form-checkbox v-model="checked" :value="agreement">
                    <span @click="toggling(agreement)" :aria-controls="agreement.type"
                          :aria-expanded="opened[agreement.type]" role="button">
                    {{agreement.name}}
                    </span>
                    </b-form-checkbox>
                </b-card-header>
                <b-collapse :id="agreement.type" :visible="opened[agreement.type]" accordion="my-agreement"
                            role="tabpanel">
                    <b-card-body>
                        <p class="card-text">
                            {{ agreement.contents }}
                        </p>
                    </b-card-body>
                </b-collapse>
            </b-card>
        </div>
    </div>
</template>

<script>
    export default {
        name: "OrderAgreementComponent",
        props: [
            'agreements', 'agreeUrl'
        ],
        watch: {
            checked(el, oldEl) {
                this.$emit('input', this.checked)
                var regDiff = $(el).not(oldEl).get()
                if(regDiff.length>0){
                    this.register(regDiff[0])
                }
                if(oldEl){

                    var remDiff = $(oldEl).not(el).get()
                    if(remDiff.length>0)
                    {
                        this.remove(remDiff[0])
                    }
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
                }
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
                console.log(agreement)
                console.log('rem...')
            }
        }
    }
</script>

<style scoped>

</style>
