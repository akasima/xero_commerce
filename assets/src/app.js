window.Vue = require('vue');

Vue.component('option-table-component', require('./components/product/OptionTableComponent').default);
Vue.component('row-component', require('./components/product/RowComponent').default);
Vue.component('delivery-component', require('./components/order/DeliveryComponent').default);

var app = new Vue({
    el: '#product-container'
});
