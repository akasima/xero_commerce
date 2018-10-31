$(function () {
    var getIndex = function ($listBox, lis) {
        return lis.index($listBox.find('li.active'));
    };

    $('.xe-shop-slider .image-list a').click(function (event) {
        event.preventDefault();

        var $this = $(this);

        if ($this.closest('li.image-list').hasClass('active')) {
            location.href = $this.data('url');
        }

        var $box = $this.closest('.xe-shop-slider'),
            $listBox = $box.find('.xe-shop-slider-view'),
            lis = $listBox.find('li.image-list')
            index = getIndex($listBox, lis),
            next = lis.index($this.closest('li.image-list'));

        $(lis[index]).removeClass('active');
        $(lis[next]).addClass('active');
    });

    $('.xe-shop-slider .btn-prev').click(function (event) {
        var $this = $(this),
            $box = $this.closest('.xe-shop-slider'),
            $listBox = $box.find('.xe-shop-slider-view'),
            lis = $listBox.find('li.image-list'),
            length = lis.length,
            index = getIndex($listBox, lis),
            next = index - 1;

        if (next < 0) {
            next = length - 1;
        }

        $(lis[index]).removeClass('active');
        $(lis[next]).addClass('active');
    });

    $('.xe-shop-slider .btn-next').click(function (event) {
        var $this = $(this),
            $box = $this.closest('.xe-shop-slider'),
            $listBox = $box.find('.xe-shop-slider-view'),
            lis = $listBox.find('li.image-list'),
            length = lis.length,
            index = getIndex($listBox, lis),
            next = index + 1;

        if (next >= length) {
            next = 0;
        }

        $(lis[index]).removeClass('active');
        $(lis[next]).addClass('active');
    });
});