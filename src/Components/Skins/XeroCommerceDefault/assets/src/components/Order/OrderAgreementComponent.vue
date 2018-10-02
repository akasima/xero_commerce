<template>
    <div role="tablist">
        <b-card v-for="(agreement, key) in agreements">
            <b-card-header role="tab">
                <b-form-checkbox v-model="checked" :value="agreement">
                    <span v-b-toggle.accordion1>
                    {{agreement.name}}
                    </span>
                </b-form-checkbox>
            </b-card-header>
            <b-collapse id="accordion1" visible accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <p class="card-text">
                        {{ agreement.contents }}
                    </p>
                </b-card-body>
            </b-collapse>
        </b-card>
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
        console.log(el)
      }
    },
    data () {
      return {
        checked: []
      }
    }
  }
</script>

<style scoped>

</style>