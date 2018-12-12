const lg = {
  name: 'lg',
  submit: function (success, fail) {
    var result = openXpay(document.getElementById('xero_pay'), document.getElementsByName('CST_PLATFORM')[0].value, 'iframe', null, "", "");
    $(result).on("load", function () {
      var iframe = result.contentWindow || result.contentDocument;
      console.log('load');
      if(iframe.document){
        $(iframe.document).on("complete", function () {
          success(
            {
              status: true
            }
          )
        })
        $(iframe.document).on("fail", function (err) {
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
