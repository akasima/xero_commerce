const payment = {

  gateway: null,

  init: function (obj) {
    if (this.validate(obj)) {
      this.gateway = obj
    } else {
      console.log('module initialized failed')
    }
  },

  validate: function (obj) {
    if (!obj.hasOwnProperty('submit')) {
      console.log('error: this payment not can be initialize')
      alert('payment error');
      return false;
    }
    return true;
  },

  submit: function (data, success, fail) {
    if(this.gateway === null){
      alert('결제모듈을 불러오는 것에 실패했습니다. 새로고침하여 다시 시도해주세요.')
      return false
    }
    var initialForm =document.getElementById('xero_pay');
    if(initialForm !== null){
      initialForm.remove()
    }
    $.ajax({
      url: '/payment/form',
      method: 'post',
      data: data
    }).done(function(res) {
      var form = document.createElement('form')
      form.setAttribute('id','xero_pay')
      form.setAttribute('method','post')
      for (var key in res) {
        var input = document.createElement('input')
        input.setAttribute('type', 'hidden')
        input.setAttribute('name', key)
        input.setAttribute('value', res[key])
        form.appendChild(input)
      }
      document.body.appendChild(form)
      this.gateway.submit(success, fail);
    }).fail(fail);
  }
}
