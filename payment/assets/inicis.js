const inicis = {
  name: 'test',
  submit (success, fail) {
    INIStdPay.pay('xero_pay');
    setTimeout(()=>{
      // INIStdPay.boolInitDone = true
      $('#inicisModalDiv').css('opacity',1);
    },500)
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
