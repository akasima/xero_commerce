const lg = {
  name: 'test',
  submit (success, fail) {
    var result = openXpay(document.getElementById('xero_pay'), 'test', 'iframe', null, "", "");
    var iframe = result.contentWindow || result.contentDocument
    this.callback(iframe, success, fail)
  },
  callback (frame, success, fail) {
    setInterval(()=>{
      if(frame.location.href ===''){
        if()
      }
    }, 500)
  }
}
$(function(){
  console.log('start init..')
  payment.init(lg)

})
