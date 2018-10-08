const test = {
  name: 'test',
  submit (success, fail) {
    $.ajax({
      url: '/payment/callback',
      method: 'post'
    }).done(res=>{
      success(res)
    }).fail(err=>{
      fail(err)
    })
  }
}
$(function(){
  console.log('start init..')
  payment.init(test)
})