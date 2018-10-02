
window.Vue = require('vue');
window.BootstrapVue = require('../../../../../assets/node_modules/bootstrap-vue');

Vue.use(BootstrapVue);

require('../../../../../assets/node_modules/bootstrap/dist/css/bootstrap.css');
require('../../../../../assets/node_modules/bootstrap-vue/dist/bootstrap-vue.css');
require('../../../../../assets/node_modules/@fortawesome/fontawesome-free/js/all.min');
require('../../../../../assets/node_modules/@fortawesome/fontawesome-free/css/all.min.css');

Vue.component('test-component', require('./components/TestComponent').default);
Vue.component('cart-component', require('./components/Cart/CartComponent').default);
Vue.component('order-register-component', require('./components/Order/OrderRegisterComponent').default);
Vue.component('dash-component', require('./components/DashComponent').default);

var app = new Vue({
  el: '#sub-container'
});