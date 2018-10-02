window.Vue = require('vue');

Vue.component('option-table-component', require('./components/product/OptionTableComponent').default);
Vue.component('show-row-component', require('./components/product/ShowRowComponent').default);
Vue.component('edit-row-component', require('./components/product/EditRowComponent').default);

var app = new Vue({
    el: '#product-container'
});
