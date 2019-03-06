<div class="container" id="contacts">
    <div>
        <h3>주문자 정보 입력</h3>
        <p>주문을 하기위해서는 주문자정보가 필요합니다.</p>
    </div>
    <form id="agreement" action="{{route('xero_commerce::agreement.contacts.save')}}" method="post">
        {{csrf_field()}}
        <div class="xe-form-group">
            <label for="">이름</label>
            <input type="text" class="xe-form-control" name="name">
        </div>
        <div class="xe-form-group">
            <label for="">휴대폰</label>
            <input type="text" class="xe-form-control" name="phone" placeholder="'-'제외">
        </div>
        <h4>{{$agreement->name}}</h4>
        <textarea class="xe-form-control" name="" id="" cols="30" rows="10" readonly>{{$agreement->contents . $agreement->contents}}</textarea>
        <input type="hidden" name="agreement_id" value="{{$agreement->id}}">
        <button class="xe-btn xe-btn-black xe-btn-block" type="submit" onclick="submitForm(event)">동의하고 진행합니다.</button>
    </form>
</div>
<script>
    function validate(){
        var validateObj = {
            status: true,
            msg: ''
        };
        if($("input[name=name]").val()==='' ||$("input[name=name]").val()===null){
            validateObj.status = false
            validateObj.msg += '이름은 필수입니다.\r\n'
        }
        if($("input[name=phone]").val()==='' ||$("input[name=phone]").val()===null){
            validateObj.status = false
            validateObj.msg += '휴대폰은 필수입니다.\r\n'
        }
        return validateObj
    }
    function submitForm(e){
        e.preventDefault()
        console.log('validate');
        var validation = validate();
        if(!validation.status) {
            alert(validation.msg);
            return false;
        }
        $('#agreement').submit();
    }
</script>
<style>
    #contacts {
        width:600px;
        margin-top: 20px;
        margin-bottom: 40px;
    }
    #contacts textarea {
        resize: none;
        background:white;
    }
</style>
