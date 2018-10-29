const lg = {
  name: 'test',
  submit(success, fail) {
    var result = openXpay(document.getElementById('xero_pay'), 'test', 'iframe', null, "", "");
    $(result).on("load", () => {
      var iframe = result.contentWindow || result.contentDocument;
      $(iframe.document).on("complete", () => {
        success(
          {
            status: true
          }
        )
      })
      $(iframe.document).on("fail", err => {
        console.log(err.msg)
        fail(err.msg)
        parent.closeIframe()
      })
    })
  }
}
$(function () {
  console.log('start init..')
  payment.init(lg)
})
