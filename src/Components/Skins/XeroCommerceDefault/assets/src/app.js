
window.Vue = require('vue');

Vue.component('test-component', require('./components/TestComponent').default);
Vue.component('cart-component', require('./components/CartComponent').default)

var app = new Vue({
  el: '#sub-container'
});