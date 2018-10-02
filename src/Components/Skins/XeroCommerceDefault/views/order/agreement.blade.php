<div class="container" id="contacts">
    <div>
        <h3>주문자 정보 입력</h3>
        <p>주문을 하기위해서는 주문자정보가 필요합니다.</p>
    </div>
    <form action="{{route('xero_commerce::agreement.contacts.save')}}" method="post">
        {{csrf_field()}}
        <div class="xe-form-group">
            <label for="">이름</label>
            <input type="text" class="xe-form-control" name="name">
        </div>
        <div class="xe-form-group">
            <label for="">휴대폰</label>
            <input type="text" class="xe-form-control" name="phone">
        </div>
        <h4>{{$agreement->name}}</h4>
        <textarea class="xe-form-control" name="" id="" cols="30" rows="10" readonly>{{$agreement->contents . $agreement->contents}}</textarea>
        <input type="hidden" name="agreement_id" value="{{$agreement->id}}">
        <button class="xe-btn xe-btn-black xe-btn-block" type="submit">동의하고 진행합니다.</button>
    </form>
</div>
<style>
    #contacts {
        width:600px;
        margin-top: 20px;
    }
    #contacts textarea {
        resize: none;
        background:white;
    }
</style>