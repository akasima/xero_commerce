<template>
    <div role="tablist">
        <b-card>
            <b-card-header role="tab">
                <b-form-checkbox v-model="agree1">
                    <span v-b-toggle.accordion1>
                    구매동의
                    </span>
                </b-form-checkbox>
            </b-card-header>
            <b-collapse id="accordion1" visible accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <p class="card-text">
                        {{ agreement1 }}
                    </p>
                </b-card-body>
            </b-collapse>
        </b-card>
        <b-card>
            <b-card-header role="tab">
                <b-form-checkbox v-model="agree2">
                    <span v-b-toggle.accordion2>
                    개인정보 수집 및 이용동의
                    </span>
                </b-form-checkbox>
            </b-card-header>
            <b-collapse id="accordion2" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <p class="card-text">
                        {{ agreement1 }}
                    </p>
                </b-card-body>
            </b-collapse>
        </b-card>
        <b-card>
            <b-card-header role="tab">
                <b-form-checkbox v-model="agree3">
                    <span v-b-toggle.accordion3>
                    개인정보 제3자 제공/위탁동의
                    </span>
                </b-form-checkbox>
            </b-card-header>
            <b-collapse id="accordion3" accordion="my-accordion" role="tabpanel">
                <b-card-body>
                    <p class="card-text">
                        {{ agreement1 }}
                    </p>
                </b-card-body>
            </b-collapse>
        </b-card>
    </div>
</template>

<script>
  export default {
    name: "OrderAgreementComponent",
    data () {
      return {
        agree1: false,
        agree2: false,
        agree3: false,
        agreement1 : 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven\'t heard of them accusamus labore sustainable VHS.-->',
        agreement2 : '',
        agreement3 : ''
      }
    }
  }
</script>

<style scoped>

</style>