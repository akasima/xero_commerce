var account = {
  name: 'lg',
  submit: function (success, fail) {
    $form = $('#xero_pay');
    $.ajax({
      url: $('[name=url]',$form).val(),
      data: $form.serialize(),
      success: function () {
        success(
          {
            status: true
          }
        );
      },
      error: function (err) {
        console.log(err)
      }
    });
  }
}
$(function () {
  console.log('start init..')
  payment.init(account);
  $('[name=pay-type]').closest('div').parent().append('<br>');
  $('[name=pay-type]').closest('div').parent().append('<strong>예금주 : '+window.accountConfig.Host+'</strong>');
  $('[name=pay-type]').closest('div').parent().append('<br>');
  $('[name=pay-type]').closest('div').parent().append('<br>');
  $('[name=pay-type]').closest('div').parent().append('<strong>계좌번호 : '+window.accountConfig.AccountNo+'</strong>');
  $('[name=pay-type]').trigger('click');
  $('[name=pay-type]').closest('div').css('visibility','hidden');
})
