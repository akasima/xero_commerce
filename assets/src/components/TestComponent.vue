<template>
    <div>
        hi! {{name}} Component!
        <second-component></second-component>
    </div>
</template>

<script>
    import SecondComponent from './SecondComponent'
  export default {
    name: "TestComponent",
    data() {
      return {
        name: 'Hero'
      }
    },
    components: {
      SecondComponent
    }
  }
</script>

<style scoped>

</style>