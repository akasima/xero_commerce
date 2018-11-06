@section('page_title')
    <h2>쇼핑몰 설정</h2>
@endsection

<form method="post" action="{{ route('xero_commerce::setting.config.store') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group shop_logo">
                            쇼핑몰 로고 (108px * 78px)
                            <img id="logoPreview" style="display:inline" name="logo"
                                 @if ($config['logo_id'] != '') src="{{ app('xero_commerce.imageHandler')->getImageUrlByFileId($config['logo_id']) }}"
                                 @else src="" @endif >
                            <input id="delLogo" type="hidden" name="logo"  value="del" disabled>
                            <input id="logoUpload" type="file" name="logo" disabled><br>
                            <label id="uploadButton" for="logoUpload" class="xe-btn xe-btn-positive">업로드</label>
                            <button id="logoDelete" type="button" class="xe-btn xe-btn-danger">삭제</button>
                        </div>

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

<style>
    .shop_logo input[type="file"] {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        border: 0 !important;
    }

    .shop_logo .logo_insert label {
        color: #ffffff !important;
        width: 40px !important;
        display: inline;
    }

    .shop_logo .logo_insert button {
        display: inline;
        margin-bottom: 0;
    }
</style>

<script>
    //사진 업로드 라벨 클릭했을 때 업로드 버튼 활성화
    $('#uploadButton').click(function (e) {
        $('#logoUpload').prop('disabled', false);
        $('#delLogo').prop('disabled', true);
    });

    //사진 선택했을 때 미리보기 출력
    $('#logoUpload').change(function (e) {
        $('#logoPreview').attr('src', URL.createObjectURL(e.target.files[0]));
        $('#logoPreview').css('display', 'inline');
    });

    //사진 삭제 버튼 클릭 했을 때 처리
    $('#logoDelete').click(function () {
        $('#logoUpload').prop('disabled', true);
        $('#delLogo').prop('disabled', false);
        $('#logoPreview').attr('src', '');
        $('#logoPreview').css('display', 'none');
    });

</script>