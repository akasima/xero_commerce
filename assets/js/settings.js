window.XE.app('Form').then(function (appForm) {
  // 상품 등록 폼
  var productForm = appForm.get($('#save'))

  // field에 포커스
  productForm.$$on('xe.validation.faield', function (eventName, data) {
    var $field = data.field
    var collapse = $field.closest('.collapse')
    if (collapse.length && !collapse.is(':visible')) {
      collapse.collapse('show')
    }
    $field.focus()
  })

  productForm.$$on('xe.valitation.required-label', function (eventName, { field, label }) {
    var headingTitle = label.closest('.panel').find('.panel-title')
    if (headingTitle.length) {
      headingTitle.addClass('xe-form__label--requried')
    }
  })
})
