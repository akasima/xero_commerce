window.jQuery(function ($) {
  var XE = window.XE
  XE.app('Form').then(function (appForm) {
    // 상품 등록 폼
    var productForm = appForm.get($('#save'))
    console.debug('productForm', productForm)

    productForm.$$on('xe.validation.faield', function (eventName, data) {
      var $field = data.field

      // $field.focus()
    })
  })
})
