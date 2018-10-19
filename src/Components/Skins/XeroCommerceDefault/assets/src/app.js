window.Vue = require('vue')
window.BootstrapVue = require('bootstrap-vue');
window.VueDaumPostcode = require('vue-daum-postcode');

Vue.use(BootstrapVue);
Vue.use(VueDaumPostcode);

require('bootstrap/dist/css/bootstrap.css');
require('bootstrap-vue/dist/bootstrap-vue.css');
require('@fortawesome/fontawesome-free/js/all.min');
require('@fortawesome/fontawesome-free/css/all.min.css');

Vue.component('test-component', require('./components/TestComponent').default);
Vue.component('product-detail-component', require('./components/Product/ProductDetailComponent').default);

var app = new Vue({
  el: '#sub-container'
});
