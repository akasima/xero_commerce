const lg = {
  name: 'test',
  submit (success, fail) {
    openXpay(document.getElementById('xero_pay'), 'test', 'iframe', null, "", "");
  }
}
$(function(){
  console.log('start init..')
  payment.init(lg)

})
