//import moment from 'moment';
if (RichShop == undefined) {
    var RichShop = {

    };
}

RichShop.SettingCategory = {
    init: function () {
        $('.xe-rich-shop-category .xe-dropdown-menu li a').bind('click', function (event) {
            event.preventDefault();
            var $target = $(event.target),
                $container = $target.closest('.xe-rich-shop-category'),
                relative = $container.data('relative');

            RichShop.SettingCategory.clear($container);

            $container.find('button.select').text($target.text());
            $container.find('input.' + relative).val($target.data('id'));

            if ($target.data('id') != '') {
                RichShop.SettingCategory.navigator($container);
                RichShop.SettingCategory.getChildren($container, $target.data('url'), $target.data('id'));
            }
        });

        $('.xe-rich-shop-category .category-add').bind('click', function (event) {
            var $target = $(event.target),
                $container = $target.closest('.xe-rich-shop-category');

            RichShop.SettingCategory.addProductCategory($container);
        });

        $('.xe-product-categories').bind('click', '.destroy', function (event) {
            console.log($(event.target));
            $(event.target).closest('li').remove();
        });

        // 이전 선택 처리
        $('.xe-rich-shop-category').each(function (index) {
            var $container = $(this),
                itemNodes = $container.data('item-nodes');
            if (itemNodes != undefined) {
                var id = itemNodes.shift();
                $container.data('item-nodes', itemNodes);
                $container.find('.xe-dropdown-menu li a[data-id="'+id+'"]').trigger('click');
            }
        });
    },
    addProductCategory: function ($container) {
        var relative = $container.data('relative'),
            $childrenContainer = $container.next();

        var $li = $('<li>'),$input, $small, $button;
        $input = $('<input>').attr('type', 'hidden').attr('name', 'categoryItemId[]').val($container.find('input.' + relative).val());
        $small = $('<small>').text($('.xe-category-navigator').filter('.'+relative).text());
        $button = $('<button>').attr('type', 'button').addClass('xe-btn-small destroy').text('X');

        $li.append($input).append($small).append($button);
        $('.xe-product-categories.'+relative).append($li);

        this.clear($container);
    },
    getChildren: function ($container, url, itemId) {
        var data = {};
        if (itemId != undefined) {
            data.id = itemId;
        }

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.length > 0) {
                    var $select = RichShop.SettingCategory.render(data, $container, url);

                    // trigger selectedItemsNodes selected event
                    var itemNodes = $container.data('item-nodes'), id;
                    if (itemNodes != undefined && itemNodes.length > 0) {
                        id = itemNodes.shift();
                        $container.data('item-nodes', itemNodes);
                        $select.find('option').each(function (index) {
                            var $_this = $(this);
                            if ($_this.attr('value') == id) {
                                $_this.attr('selected', 'selected');
                                $_this.closest('select').trigger('change');
                                return;
                            }
                        });
                    }
                }
            }
        });
    },
    render: function (items, $container, url) {

        var $childrenContainer = $container.next();

        var $select = $('<select>').addClass('form-control');

        var $option, item;
        $option = $('<option>').val('').text('선택');
        $option.appendTo($select);
        for(var i in items) {
            item = items[i];
            $option = $('<option>').val(item.id).text(item.readableWord);
            $option.appendTo($select);
        }

        $select.appendTo($childrenContainer);
        // bind events
        $select.bind('change', function (event) {
            var $target = $(event.target),
                $selectedOption = $target.find('option:selected');

            var $next = $target.next();
            if ($next != undefined) {
                $next.remove();
                $next.trigger('remove');
            }

            RichShop.SettingCategory.navigator($container, $selectedOption);

            if ($selectedOption.val() != '') {
                RichShop.SettingCategory.getChildren($container, url, $selectedOption.val());
            }
        }).bind('remove', function (event) {
            $(event.target).next().remove();
        });

        return $select;
    },
    navigator: function ($container, $selectedOption) {
        var relative = $container.data('relative'),
            $childrenContainer = $container.next();

        var paths = [
            $container.find('button.select').text()
        ];

        if ($selectedOption != undefined) {
            $container.find('input.' + relative).val($selectedOption.val());
        }

        $childrenContainer.find('select').each(function() {
            var $selected = $(this).find('option:selected');
            if ($selected.val() != '') {
                paths.push($selected.text());
            }
        });

        $container.find('input.' + relative + '-depth').val(paths.length);

        $('.xe-category-navigator').filter('.'+relative).text(paths.join(' > '));
    },
    clear: function ($container) {
        var relative = $container.data('relative'),
            $childrenContainer = $container.next();

        $childrenContainer.empty();
        $container.find('button.select').text($container.find('.xe-dropdown-menu>li:first').text());
        $container.find('input.' + relative).val('');
        $('.xe-category-navigator').filter('.'+relative).text('');
    }
};

RichShop.SettingImage = {
    init: function () {
        this.initProductImage();
        this.initProductDetailImage();
        // 썸네일 업로드 처리
        this.initThumbnailImage();
    },
    initProductImage: function () {
        $('.xe-rich-shop-product-image').each(function (index) {
            var $box = $(this),
                options = RichShop.SettingImage.uploadOptions($box)
                id = $box.data('id');

            if (id != undefined && id != '') {
                $box.nextAll('.thumbnail-zone').show();
            }

            $box.fileupload($.extend(options, {
                done: function (e, data) {
                    var file = data.result.file
                        , fileName = file.clientname
                        , fileSize = file.size
                        , mime = file.mime
                        , id = file.id;

                    var imgs = $('.xe-rich-shop-imagebox .preview');
                    $('[name="product_image_file"]').val(file.id);
                    $('.thumbnail-zone').show();

                    for (var i = 0; i < data.result.thumbnails.length; i++) {
                        var $o = $(imgs[i]);
                        $o.attr('src', data.result.thumbnails[i].url);
                        $o.closest('.xe-rich-shop-imagebox').attr('data-id', data.result.thumbnails[i].id);

                    }
                }
            }));
        });
    },

    initProductDetailImage: function () {
        var $box = $('.xe-rich-shop-product-detail-image'),
            options = RichShop.SettingImage.uploadOptions($box)
        id = $box.data('id');

        if (id != undefined && id != '') {
            $box.nextAll('.thumbnail-zone').show();
        }

        $box.fileupload($.extend(options, {
            done: function (e, data) {
                var file = data.result.file
                    , fileName = file.clientname
                    , fileSize = file.size
                    , mime = file.mime
                    , id = file.id;

                var $thumbnailZone = $('.thumbnail-detail-zone'),
                    $input = $('<input type="hidden" name="product_detail_image_file[]">').val(file.id);
                $thumbnailZone.show();

                var $div = $('<div class="col-sm-2">'),
                    $img = $('<img>'),
                    thumb = data.result.thumbnails.pop();

                if (thumb == undefined || thumb == false) {
                    XE.toast("xe-warning", "정상적이지 않은 썸네일 정보.");

                }
                $img.attr('src', thumb.url);
                $thumbnailZone.append($div.append($img).append($input));
            }
        }));
    },

    initThumbnailImage: function () {
        $('.xe-rich-shop-imagebox').each(function(index) {
            var $box = $(this),
                options = RichShop.SettingImage.uploadOptions($box);

            $box.fileupload($.extend(options, {
                done: function (e, data) {
                    console.log(data);
                    var thumbnail = data.result.thumbnail;

                    $box.find('img').attr('src', thumbnail.url);
                    $box.attr('data-id', thumbnail.id);
                }
            }));
        });
    },

    uploadOptions: function ($box) {
        var extensions = ['jpg', 'jpeg', 'gif', 'png'];
        var attachMaxSize = 5; // MB
        return {
            url: $box.data('url'),
            type: "post",
            dataType: 'json',
            sequentialUploads: true,
            autoUpload: false,
            dropZone: $box.filter(".dropZone"),
            paramName: 'image',
            maxFileSize: attachMaxSize * 1024 * 1024,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
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

                //[2]파일 사이즈
                if (fSize > attachMaxSize * 1024 * 1024) {
                    XE.toast("xe-warning", "파일 용량은 " + attachMaxSize + "MB를 초과할 수 없습니다. [" + uploadFileName + "]");
                    valid = false;
                }

                if (valid) {
                    data.formData = {};
                    data.formData.id = $box.attr('data-id');

                    data.submit();
                } else {
                    //$dropZone.removeClass("drag");
                }
            },
            fail: function () {
                console.log('fail');
            }
        };
    }
};

RichShop.SettingOption = {
    init: function () {
        $('.xe-add-option').bind('click', function() {
            $('.xe-option-modal').modal('toggle');
        });
        $('.xe-edit-option').bind('click', function () {
            var $modal = $('.xe-option-edit-modal'),
                $button = $(this);

            $modal.find('form').attr('action', $button.data('action'));
            $modal.find('[name="id"]').val($button.data('id'));
            $modal.find('[name="option_name"]').val($button.closest('tr').find('td:nth-child(1)').text());
            $modal.find('[name="additional_price"]').val($button.closest('tr').find('td:nth-child(2)').text());
            $modal.find('[name="stock_quantity"]').val($button.closest('tr').find('td:nth-child(3)').text());
            $modal.modal('toggle');
        });
        $('.xe-period input:nth-child(1)').on('change', function(event) {
            var $target = $(event.target),
                $container = $target.closest('.xe-period'),
                period = $target.val();

            var startDate = '',
                endDate = moment().format('YYYY-MM-DD'),
                $startDate = $container.find('dates input:nth-child(1)'),
                $endDate = $container.find('dates input:nth-child(2)');

            var parts = period.slice(',');
            startData = moment().add(-1 * parseInt(parts[0]), parts[1]).format('YYYY-MM-DD');

            $startDate.val(startDate);
            $endDate.val(endDate);
        });
    }

};

$(function() {
    // 카테고리 선택
    RichShop.SettingCategory.init();

    // 상품 이미지 등록
    RichShop.SettingImage.init();

    // 상품 상세 옵션 설정
    RichShop.SettingOption.init();
});

$('.__xe_btn_search_target .dropdown-menu a').click(function (e) {
    e.preventDefault();

    $('[name="search_target"]').val($(this).attr('value'));
    $('.__xe_btn_search_target .__xe_text').text($(this).text());

    $(this).closest('.dropdown-menu').find('li').removeClass('active');
    $(this).closest('li').addClass('active');
});