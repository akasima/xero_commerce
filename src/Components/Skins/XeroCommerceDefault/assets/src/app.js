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
Vue.component('cart-component', require('./components/Cart/CartComponent').default);
Vue.component('order-register-component', require('./components/Order/OrderRegisterComponent').default);
Vue.component('dash-component', require('./components/DashComponent').default);
Vue.component('order-list-component', require('./components/Order/OrderListComponent').default);
Vue.component('order-detail-component', require('./components/Order/OrderDetailComponent').default);

var app = new Vue({
  el: '#sub-container'
});