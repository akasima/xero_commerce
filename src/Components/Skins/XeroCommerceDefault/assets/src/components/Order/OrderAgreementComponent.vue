<template>
    <div>
        <div role="tablist">
            <b-card v-for="(agreement, key) in agreements">
                <b-card-header role="tab">
                    <b-form-checkbox v-model="checked" :value="agreement">
                    <span @click="toggling(agreement)" :aria-controls="agreement.type" :aria-expanded="opened[agreement.type]"  role="button" >
                    {{agreement.name}}
                    </span>
                    </b-form-checkbox>
                </b-card-header>
                <b-collapse :id="agreement.type"  :visible="opened[agreement.type]" accordion="my-agreement" role="tabpanel">
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
      'agreements'
    ],
    watch: {
      checked (el) {
        this.$emit('input', this.checked)
      }
    },
    data () {
      return {
        checked: [],
        opened: {
          purchase: true,
          privacy: false,
          thirdParty:false
        }
      }
    },
    methods: {
      toggling (agreement) {
        this.opened.purchase = false
        this.opened.privacy = false
        this.opened.thirdParty = false
        this.opened[agreement.type] =! this.opened[agreement.type]
      }
    }
  }
</script>

<style scoped>

</style>