const lg = {
  name: 'test',
  submit(success, fail) {
    var result = openXpay(document.getElementById('xero_pay'), 'test', 'iframe', null, "", "");
    $(result).on("load", () => {
      var iframe = result.contentWindow || result.contentDocument;
      console.log('load');
      if(iframe.document){
        $(iframe.document).on("complete", () => {
          success(
            {
              status: true
            }
          )
        })
        $(iframe.document).on("fail", err => {
          fail(err.detail.msg)
          parent.closeIframe()
        })
      }
    })
  }
}
$(function () {
  console.log('start init..')
  payment.init(lg)
})
