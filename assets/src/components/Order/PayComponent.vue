<template>
    <div>
        <b-form-radio-group v-model="payMethod"  >
            <b-form-radio v-for="(method,key) in payMethods" :value="key" :key="key">{{method}}</b-form-radio>
        </b-form-radio-group>
    </div>
</template>

<script>
  export default {
    name: "PayComponent",
    props:[
        'payMethods'
    ],
    watch: {
      payMethod (el) {
        this.$emit('input',el)
      }
    },
    data () {
      return {
        methods: [],
        payMethod: null
      }
    },
    mounted () {
      this.methods = this.payMethods
    }
  }
</script>

<style scoped>

</style>