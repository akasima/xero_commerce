@section('page_title')
    <h2>쇼핑몰 설정</h2>
@endsection

<form method="post" action="{{ route('xero_commerce::setting.config.store') }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group">
                            회사명
                            <input type="text" name="companyName" value="{{ $config['companyName'] }}">
                        </div>

                        <div class="form-group">
                            사업자번호
                            <input type="text" name="companyNumber" value="{{ $config['companyNumber'] }}">
                        </div>

                        <div class="form-group">
                            대표자명
                            <input type="text" name="ceoName" value="{{ $config['ceoName'] }}">
                        </div>

                        <div class="form-group">
                            전화
                            <input type="text" name="telNumber" value="{{ $config['telNumber'] }}">
                        </div>

                        <div class="form-group">
                            팩스
                            <input type="text" name="faxNumber" value="{{ $config['faxNumber'] }}">
                        </div>

                        <div class="form-group">
                            통신판매업 신고번호
                            <input type="text" name="communicationMarketingNumber" value="{{ $config['communicationMarketingNumber'] }}">
                        </div>

                        <div class="form-group">
                            부가통신 사업자번호
                            <input type="text" name="additionCommunicationNumber" value="{{ $config['additionCommunicationNumber'] }}">
                        </div>


                        <div class="form-group">
                            우편번호
                            <input type="text" name="zipCode" value="{{ $config['zipCode'] }}">
                        </div>

                        <div class="form-group">
                            주소
                            <input type="text" name="address" value="{{ $config['address'] }}">
                        </div>

                        <div class="form-group">
                            정보 관리 책임자
                            <input type="text" name="informationCharger" value="{{ $config['informationCharger'] }}">
                        </div>

                        <div class="form-group">
                            이메일
                            <input type="text" name="email" value="{{ $config['email'] }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="xe-btn xe-btn-success">등록</button>
</form>
