if (Widget == undefined) {
    var Widget = {

    };
}

Widget.SettingImage = {
    init: function () {
        this.initProductImage();
    },

    initProductImage: function () {
        $('.rich-shop-widget-thumb').each(function(index) {
            var $box = $(this),
                options = Widget.SettingImage.uploadOptions($box);

            $box.fileupload($.extend(options, {
                done: function (e, data) {
                    console.log(data);
                    var thumbnails = data.result.thumbnails,
                        file = data.result.file,
                        img = $('<img>');


                    // 이전 이미지 제거
                    $box.find('.img img').remove();

                    img.attr('src', thumbnails[0].url);
                    $box.find('.img').append(img);
                    $box.find('.file-id').val(file.id);
                }
            }));
        });
    },

    uploadOptions: function ($box) {
        var extensions = ['jpg', 'jpeg', 'gif', 'png'];
        var attachMaxSize = 10; // MB
        return {
            url: $box.data('url'),
            type: "post",
            dataType: 'json',
            sequentialUploads: true,
            autoUpload: false,
            dropZone: $box.filter(".dropZone"),
            paramName: 'image',
            maxFileSize: attachMaxSize * 1024 * 1024,
            add: function (e, data) {
                var valid = true,
                    extValid = false,
                    files = data.files,
                    uploadFileName = files[0].name,
                    fSize = files[0].size;

                for (var i = 0; i < extensions.length; i++) {
                    var sCurExtension = extensions[i];

                    if (sCurExtension === '*') {
                        extValid = true;
                        break;
                    } else if (uploadFileName.substr(uploadFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() === sCurExtension.toLowerCase()) {
                        extValid = true;
                        break;
                    }
                }

                //[1]확장자
                if (!extValid) {
                    XE.toast("xe-warning", "[" + extensions.join(", ") + "] 확장자만 업로드 가능합니다. [" + uploadFileName + "]");
                    valid = false;
                }

                if (valid) {
                    data.formData = {};
                    data.formData.id = $box.attr('data-id');

                    data.submit();
                }
            },
            fail: function () {
                console.log('fail');
            }
        };
    }
};