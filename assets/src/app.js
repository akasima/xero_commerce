window.Vue = require('vue');

Vue.component('option-table-component', require('./components/product/OptionTableComponent').default);
Vue.component('row-component', require('./components/product/RowComponent').default);
Vue.component('category-component', require('./components/category/CategoryComponent').default);
Vue.component('create-category-component', require('./components/category/CreateCategoryComponent').default);
Vue.component('category-select-component', require('./components/category/CategorySelectComponent').default);
Vue.component('delivery-component', require('./components/order/DeliveryComponent').default);
Vue.component('after-service-component', require('./components/order/AfterServiceComponent').default);
Vue.component('user-search-component', require('./components/UserSearchComponent').default);

var app = new Vue({
    el: '#component-container'
});
