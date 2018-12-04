const inicis = {
  name: 'test',
  submit: function (success, fail) {
    INIStdPay.pay('xero_pay');
    setTimeout(function () {
      // INIStdPay.boolInitDone = true
      $('#inicisModalDiv').css('opacity',1);
    },500)
    $(INIStdPay.$iframe[0]).on("load",function () {
      var iframe = INIStdPay.$iframe[0].contentWindow || INIStdPay.$iframe[0].contentDocument;
      $(iframe.document).on("complete", function ()  {
        success(
          {
            status: true
          }
        )
      })
      $(iframe.document).on("fail", function(err, data) {
        fail(err.detail.msg)
        INIStdPay.viewOff()
      })
    })
  }
}
$(function(){
  console.log('start init..')
  payment.init(inicis)
  // console.log(INIStdPay)
  INIStdPay.init()
  setTimeout(function () {

    $jINICSSLoader.loadDefault();

    INIStdPay.boolMobile = $jINI.mobileBrowser;

    // windows 8 일때 체크
    if("msie" == $jINI.ua.browser.name){

      try {
        new ActiveXObject("");

      }
      catch (e) {
        // FF has ReferenceError here
        if (e.name == 'TypeError' || e.name == 'Error') {

        }else{
          INIStdPay.boolMobile = true;
          INIStdPay.boolWinMetro = true;
        }

      }
    }

    if(!INIStdPay.boolMobile){
      INIStdPay.INIModal_init();
    }
    INIStdPay.boolInitDone = true;
  },1000)
})
