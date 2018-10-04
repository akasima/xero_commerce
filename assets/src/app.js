window.Vue = require('vue');

Vue.component('option-table-component', require('./components/product/OptionTableComponent').default);
Vue.component('row-component', require('./components/product/RowComponent').default);

var app = new Vue({
    el: '#product-container'
});
