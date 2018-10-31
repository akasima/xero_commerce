if (RichShop == undefined) {
    var RichShop = {

    };
}

RichShop.ShopProduct = {
    init: function () {
        $('.select-option-container .options .xe-select-option').bind('change', function (event) {
            RichShop.ShopProduct.selectOption($(event.target));
            RichShop.ShopProduct.amount();
        });

        $('.select-option-container .xe-add-cart').bind('click', function (event) {
            RichShop.ShopProduct.addCart($(event.target));
        });

        $('.select-option-container .selected').on('click', '.remove', function (event) {
            var $target = $(event.target);
            $target.closest('.counter').remove();
            RichShop.ShopProduct.amount();
        }).on('click', '.increase', function (event) {
            RichShop.ShopProduct.optionsOrderQuantity($(event.target), 1);
            RichShop.ShopProduct.amount();
        }).on('click', '.decrease', function (event) {
            RichShop.ShopProduct.optionsOrderQuantity($(event.target), -1);
            RichShop.ShopProduct.amount();
        });

        this.photoNavigator($('.product-img-view'), $('.product-img-list'));
    },
    selectOption: function($target) {
        var $selectedOption = $target.find('option:selected'),
            $selected = $('.select-option-container .selected'),
            $counter = $('<div class="product-info-low xe-border-top xe-border-bottom counter">');

        // 이미 추가된 옵션
        if ($selected.find('div.counter[data-id="'+$selectedOption.data('id')+'"]').length != 0) {
            return;
        }

        $counter.attr('data-id', $selectedOption.data('id'));
        $counter.append($('<div class="product-info-cell">'+$selectedOption.text()+'</div>'));

        var $infoCell = $('<div class="product-info-cell"></div>');
        $infoCell.html(
            '<div class="xe-spin-box">'
            + '<button type="button" class="decrease"><i class="xi-minus-thin"></i><span class="xe-sr-only">감소</span></button>'
            + '<p class="orderQuantity">1</p>'
            + '<button type="button" class="increase"><i class="xi-plus-thin"></i><span class="xe-sr-only">증가</span></button>'
            + '</div>'
            + '<p><span class="price" data-price="'+$selectedOption.data('price')+'">'+$selectedOption.data('price')+'</span>원</p>'
            + '<button type="button" class="xe-btn xe-btn-remove remove"><i class="xi-close-thin"></i><span class="xe-sr-only">이 옵션 삭제</span></button>'
            + '</div>'
        );

        $infoCell.prepend(
            $('<input type="hidden" name="orderPrice[]" value="' + $selectedOption.data('price') + '">')
        ).prepend(
            $('<input type="hidden" name="orderQuantity[]" value="1">')
        ).prepend(
            $('<input type="hidden" name="orderOptionId[]" value="' + $selectedOption.data('id') + '">')
        );

        $counter.append($infoCell);
        $selected.append($counter);
    },
    optionsOrderQuantity: function ($target, quantity) {
        var $container = $target.closest('.counter'),
            $orderQuantity = $container.find('.orderQuantity'),
            currentQuantity = parseInt($orderQuantity.text());

        var changed = currentQuantity + quantity;
        // 수량은 항상 1 이상
        if (changed <= 0) {
            return;
        }

        $orderQuantity.text(changed);
        $container.find('[name="orderQuantity[]"]').val(changed);

        var orderPrice = parseInt($container.find('.price').data('price')) * changed;
        $container.find('[name="orderPrice[]"]').val(orderPrice);
        $container.find('.price').text(orderPrice);
    },
    amount: function () {
        var $amount = $('.select-option-container .amount'),
            $container = $('.select-option-container .selected'),
            objOrderOptionIds = $container.find('[name="orderOptionId[]"]'),
            objOrderQuantities = $container.find('[name="orderQuantity[]"]'),
            objOderPrices = $container.find('[name="orderPrice[]"]');

        if (objOrderOptionIds.length == 0) {
            $amount.hide();
        } else if (objOrderOptionIds.length == 1) {
            $amount.find('.price').text(objOderPrices.val());
            $amount.find('.quantity').text(objOrderQuantities.val());
            $amount.show();
        } else {
            var price = 0, quantity = 0;

            for (var i = 0; i < objOrderOptionIds.length; i++) {
                price += parseInt($(objOderPrices[i]).val());
                quantity += parseInt($(objOrderQuantities[i]).val());
            }
            $amount.find('.price').text(price);
            $amount.find('.quantity').text(quantity);
            $amount.show();
        }
    },
    addCart: function ($target) {
        var $container = $('.select-option-container'),
            $selected = $container.find('.selected'),
            $amount = $container.find('.amount'),
            objOrderOptionIds = $container.find('[name="orderOptionId[]"]'),
            objOrderQuantities = $container.find('[name="orderQuantity[]"]'),
            objOderPrices = $container.find('[name="orderPrice[]"]');

        if (objOrderOptionIds.length == 0) {
            XE.toast('warnning', '상품을 선택해 주세요.');
            return;
        }

        var orderOptionIds = [],
            orderQuantities = [],
            orderPrices = [];

        if (objOrderOptionIds.length == 1) {
            orderOptionIds.push(objOrderOptionIds.val());
            orderQuantities.push(objOrderQuantities.val());
            orderPrices.push(objOderPrices.val());
        } else {
            for (var i = 0; i < objOrderOptionIds.length; i++) {
                orderOptionIds.push($(objOrderOptionIds[i]).val());
                orderQuantities.push($(objOrderQuantities[i]).val());
                orderPrices.push($(objOderPrices[i]).val());
            }
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                product_id: $container.closest('form').find('[name="product_id"]').val(),
                order_option_id: orderOptionIds,
                order_quantity: orderQuantities,
                order_price: orderPrices,
                _token: $container.closest('form').find('[name="_token"]').val(),
            },
            url: $target.data('url'),
            success: function(response) {
                XE.toast('success', '카트에 담았습니다. (카트로 이동?)');
            }
        });
    },
    photoNavigator: function ($viewerBox, $listBox) {
        // triger 'focus'
        var $viewImage = $viewerBox.find('.photo'),
            $li = $listBox.find('li'),
            length = $li.length,
            index = 0;

        $listBox.find('a').bind('click', function (event) {
            event.preventDefault();
            var $curFocus = $listBox.find('.focus'),
                $this = $(this),
                $next = $this.closest('li'),
                source = $next.find('img').data('viewer-url');

            index = $next.index();

            $curFocus.removeClass('focus');
            $next.addClass('focus');
            $viewImage.attr('src', source);
        });

        $viewerBox.find('button.left').click(function () {
            var $curFocus = $($li[index]),
                next = index - 1;

            if (next < 0) {
                next = length - 1;
            }
            var $next = $($li[next]),
                source = $next.find('img').data('viewer-url');

            index = next;

            $curFocus.removeClass('focus');
            $next.addClass('focus');
            $viewImage.attr('src', source);
        });
        $viewerBox.find('button.right').click(function () {
            var $curFocus = $($li[index]),
                next = index + 1;

            if (next >= length) {
                next = 0;
            }

            index = next;

            var $next = $($li[next]),
                source = $next.find('img').data('viewer-url');

            $curFocus.removeClass('focus');
            $next.addClass('focus');
            $viewImage.attr('src', source);
        });
    }
};

RichShop.ShopCart = {
    init: function () {
        $('.cart-list .check-all.check-relative').bind('click', function (event) {
            RichShop.ShopCart.checkAll($(event.target));
            RichShop.ShopCart.summary();
        });
        $('.cart-list .option-select').bind('click', function (event) {
            RichShop.ShopCart.summary();
        });
        $('.cart-list .decrease').bind('click', function (event) {
            var $quantity = $(this).closest('.option-box').find('.quantity');
            RichShop.ShopCart.updateQuantity($quantity, -1);
        });
        $('.cart-list .increase').bind('click', function (event) {
            var $quantity = $(this).closest('.option-box').find('.quantity');
            RichShop.ShopCart.updateQuantity($quantity, +1);
        });
        $('.cart-list .deleteCartItem').bind('click', function (event) {
            RichShop.ShopCart.deleteCartItem($(this).data('url-delete-carts'));
        });
    },
    checkAll: function ($target) {
        var objDestinations = $($target.data('relative')),
            checked = $target.prop('checked');

        if (objDestinations.length === 0) {
            XE.toast('warnning', '상품이 없습니다');
            return;
        }
        if (objDestinations.length === 1) {
            $(objDestinations).prop('checked', checked);
        } else {
            for (var i=0; i<objDestinations.length; i++) {
                $(objDestinations[i]).prop('checked', checked);
            }
        }
    },
    summary: function () {
        var amount = 0,
            deliveryFee = 0;

        $('.cart-list [name="id[]"]').each(function () {
            var $target = $(this);
            if ($target.prop('checked') === true) {
                amount += $target.data('amount');
                deliveryFee += $target.data('delivery-fee');
            }
        });

        var $summary = $('.summary');
        $summary.find('.amount').text(amount);
        $summary.find('.deliveryFee').text(deliveryFee);
        $summary.find('.sum').text(amount + deliveryFee);
    },
    updateQuantity: function ($quantity, change) {
        var $optionBox = $quantity.closest('.option-box'),
            quantity = parseInt($quantity.text());
        quantity = quantity + change;

        if (quantity < 1) {
            XE.toast('warning', '하나 이상은 주문해야 한다.');
            return;
        }

        var $cartOption = $optionBox.find('[name="id[]"]');

        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                cartOptionId: $cartOption.val(),
                quantity: quantity
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $optionBox.data('url-update-quantity'),
            success: function(response) {
                $optionBox.find('.quantity').text(response.quantity);
                $optionBox.find('.amount').text(response.amount);

                $optionBox.find('[name="id[]"]')
                    .data('amount', response.amount)
                    .data('delivery-fee', response.deliveryFee);

                $quantity.text(response.quantity);

                RichShop.ShopCart.summary();
            }
        });
    },
    deleteCartItem: function (url) {
        var checkItems = [];

        $('[name="id[]"]').each(function () {
            if ($(this).prop('checked') === true) {
                checkItems.push($(this).val());
            }
        });

        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {
                checkItems: checkItems
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            success: function (response) {
                location.reload();
            }
        });
    }
};

RichShop.ShopOrder = {
    init: function () {
        $('.start-payment').click(function () {
            var $btn = $(this),
                $form = $btn.closest('form')

            $btn.attr('disabled', true);

            try {
                this.formCheck($form);
            } catch(e) {
                $btn.attr('disabled', false);
            }

            var params = $form.serialize();

            $.ajax({
                type: 'post',
                dataType: 'json',
                data: params,
                url: $btn.data('url'),
                success: function(response) {
                    console.log(response);

                    var method = $('.pay-method:checked').val();
                    XEpayment.setOrderId(response.id);
                    XEpayment.setCallback($btn.data('callback'));
                    XEpayment.exec(method);
                },
                fail: function() {
                    $btn.attr('disabled', false);
                }
            });
        });
    },
    formCheck: function ($form) {
        // check shipping
        // check agreement of terms
        // check payment type selected
    }
};

$(function() {
    RichShop.ShopProduct.init();
    RichShop.ShopCart.init();
    RichShop.ShopOrder.init();
});