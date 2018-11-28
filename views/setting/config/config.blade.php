@section('page_title')
<h2>쇼핑몰 설정</h2>
@endsection

<form method="post" action="{{ route('xero_commerce::setting.config.store') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="panel">
        <div class="panel-body">
            <div class="form-group shop_logo">
                @php
                if ($config['logo_id'] != '') {
                    $logoUrl = app('xero_commerce.imageHandler')->getImageUrlByFileId($config['logo_id']);
                } else {
                    $logoUrl = '';
                }
                @endphp
                <label>쇼핑몰 로고</label>
                <small>(108px * 78px)</small>
                <br>
                <img id="logoPreview" style="display:inline" name="logo" src="{{ $logoUrl }}">
                <input id="delLogo" type="hidden" name="logo" value="del" disabled>
                <input id="logoUpload" type="file" name="logo" disabled><br>
                <label id="uploadButton" for="logoUpload" class="btn btn-info">파일찾기</label>
                <button id="logoDelete" type="button" class="btn xe-danger">삭제</button>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6 right-division">
                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">회사명</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="companyName" value="{{ $config['companyName'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">사업자번호</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="companyNumber" value="{{ $config['companyNumber'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">대표자명</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="ceoName" value="{{ $config['ceoName'] }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">통신판매업 <br>신고번호</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="communicationMarketingNumber" value="{{ $config['communicationMarketingNumber'] }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">부가통신 <br>사업자번호</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="additionCommunicationNumber" value="{{ $config['additionCommunicationNumber'] }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 right-division">
                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">전화</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="telNumber" value="{{ $config['telNumber'] }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">팩스</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="faxNumber" value="{{ $config['faxNumber'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">정보 관리 책임자</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="informationCharger" value="{{ $config['informationCharger'] }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">우편번호</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="zipCode" value="{{ $config['zipCode'] }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">주소</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="address" value="{{ $config['address'] }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pwd" class ="control-label col-sm-3">이메일</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="email" value="{{ $config['email'] }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="xero-settings-control-float">
        <button type="submit" class="btn btn-primary btn-lg">등록</button>
    </div>
</form>

<style>
    #logoPreview {
        max-width: 108px;
        max-height: 78px;
    }
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
    .control-label{
        text-align: center;
    }
    .right-division{
        border-right:1px #ddd solid;
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
