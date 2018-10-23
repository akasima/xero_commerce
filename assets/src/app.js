window.Vue = require('vue');

window.BootstrapVue = require('bootstrap-vue');
window.VueDaumPostcode = require('vue-daum-postcode');

Vue.use(BootstrapVue);
Vue.use(VueDaumPostcode);

require('bootstrap/dist/css/bootstrap.css');
require('bootstrap-vue/dist/bootstrap-vue.css');
require('@fortawesome/fontawesome-free/js/all.min');
require('@fortawesome/fontawesome-free/css/all.min.css');

Vue.component('option-table-component', require('./components/product/OptionTableComponent').default);
Vue.component('row-component', require('./components/product/RowComponent').default);
Vue.component('category-component', require('./components/category/CategoryComponent').default);
Vue.component('create-category-component', require('./components/category/CreateCategoryComponent').default);
Vue.component('category-select-component', require('./components/category/CategorySelectComponent').default);
Vue.component('delivery-component', require('./components/setting/order/DeliveryComponent').default);
Vue.component('after-service-component', require('./components/setting/order/AfterServiceComponent').default);
Vue.component('user-search-component', require('./components/UserSearchComponent').default);
Vue.component('cart-component', require('./components/Cart/CartComponent').default);
Vue.component('order-register-component', require('./components/Order/OrderRegisterComponent').default);
Vue.component('order-list-component', require('./components/Order/OrderListComponent').default);
Vue.component('order-detail-component', require('./components/Order/OrderDetailComponent').default);
Vue.component('order-after-service-component', require('./components/Order/OrderAfterServiceComponent').default);
Vue.component('dash-component', require('./components/DashComponent').default);
Vue.component('shop-delivery-component', require('./components/setting/shop/DeliveryComponent').default);

var app = new Vue({
    el: '#component-container'
});
