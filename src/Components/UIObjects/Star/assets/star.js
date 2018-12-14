
$(function(){

  Vue.component('star-rating', VueStarRating.default);
  var a= new Vue({
    el:'#star',
    props:['star'],
    mounted: function(){
      console.log('mount');
    }
  })
});
