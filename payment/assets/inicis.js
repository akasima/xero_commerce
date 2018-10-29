const inicis = {
  name: 'test',
  submit (success, fail) {
    INIStdPay.pay('xero_pay');
    setTimeout(()=>{
      // INIStdPay.boolInitDone = true
      $('#inicisModalDiv').css('opacity',1);
    },500)
    $(INIStdPay.$iframe[0]).on("load", () => {
      var iframe = INIStdPay.$iframe[0].contentWindow || INIStdPay.$iframe[0].contentDocument;
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
$(function(){
  console.log('start init..')
  payment.init(inicis)
  // console.log(INIStdPay)
  INIStdPay.init()
  setTimeout(()=>{

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
