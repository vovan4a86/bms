function changeWeight(elem) {
    const b = document.querySelector('.button.button--primary');
    let buttonWeight = b.dataset.weight;
    let buttonSize = b.dataset.size;
    let buttonPrice = b.dataset.price;
    let buttonTotal = b.dataset.total;
    let buttonK = b.dataset.k;

    buttonPrice = buttonPrice.replace(/ /g, '');

    let sizeInput = $('.prod-order__input[name=size]');
    let totalDiv = $('.prod-order__input[name=total]');
    let weight = $(elem).val();
    let size = sizeInput.text();

    let resSize = Math.ceil(weight / buttonK);
    let res = new Intl.NumberFormat('ru-RU').format(weight * buttonPrice)

    b.dataset.weight = weight;
    b.dataset.size = size;
    b.dataset.total = res;

    sizeInput.val(resSize);
    totalDiv.text(res);
}

function changeSize(elem) {
    const b = document.querySelector('.button.button--primary');
    let buttonWeight = b.dataset.weight;
    let buttonSize = b.dataset.size;
    let buttonPrice = b.dataset.price;
    let buttonTotal = b.dataset.total;
    let buttonK = b.dataset.k;

    buttonPrice = buttonPrice.replace(/ /g, '');

    let weightInput = $('.prod-order__input[name=weight]');
    let totalDiv = $('.prod-order__input[name=total]');
    let size = $(elem).val();
    let weight = weightInput.val();

    let pr;
    if (buttonK < 1)
        pr = 1000;
    else if (buttonK < 10)
        pr = 100;
    else if (buttonK < 100)
        pr = 10;
    else if (buttonK < 1000)
        pr = 1;
    else
        pr = 0.1;

    let k = Math.ceil(buttonK * pr);
    let perMetr = k / pr;
    let weightRes = perMetr * size
    weightInput.val(weightRes);

    let total = new Intl.NumberFormat('ru-RU').format(weightRes * buttonPrice)

    b.dataset.weight = String(weightRes);
    b.dataset.size = size;
    b.dataset.total = String(weightRes * buttonPrice);
    totalDiv.text(total);
}

function changeWeightPopup(elem) {
    let priceDiv = $('.prod-order__input[name=price]');
    let sizeDiv = $('.prod-order__input[name=size]');
    let totalDiv = $('.prod-order__input[name=total]');
    let price = $('[data-order-price]');
    let total = $('[data-order-total]');
    let weight = $(elem).val();
    let size = sizeDiv.val();

    let priceVal = price.text().replace(/ /g, '');

    let res = weight * +priceVal;

    total.text(new Intl.NumberFormat('ru-RU').format(res));
}

function cartUpdateCount(elem, id, price) {
    let count = $(elem).val();
    let cardSum = $('[data-id=' + id + ']');
    // cardSum.text(count * price);
    console.log(count);
}

function purgeCart() {
    Cart.purge(function (res) {
        $('.basket').replaceWith(res.header_cart);
        $('.cart__aside').replaceWith(res.order_total);
        $('.cart-table__row--body').remove();
        // location.reload();
    }.bind(this));
}

function addToCartProductPopup(form, e) {
    e.preventDefault()
    const id = form.id;
    const weight = form.weight.value;
    const size = form.size.value;

    Cart.add(id, size, weight, function (res) {
        $('.basket').replaceWith(res.header_cart);
    }.bind(this));

    $('.is-close').click();
}

function addToCart(id) {
    Cart.add(id, 0, 1, function (res) {
        $('.basket').replaceWith(res.header_cart);
    }.bind(this));
}

function sendOrder(form, e) {
    e.preventDefault();
    var data = $(form).serialize();
    var url = $(form).attr('action');
    sendAjax(url, data, function (json) {
        if (typeof json.errors != 'undefined') {
            // validForm($(form), json.errors);
            var errMsg = [];
            for (var key in json.errors) {
                errMsg.push(json.errors[key]);
            }
            var strError = errMsg.join('<br />');
            $(form).find('[type="submit"]').after('<div class="err-msg-block">' + strError + '</div>');
        }
        if (json.success !== true) {
            console.log('Что-то не так');
        } else {
            resetForm(form);
            Fancybox.show([{src: '#order-done', type: 'inline'}], {
                mainClass: 'popup--custom popup--complete',
                template: {closeButton: closeBtn},
                hideClass: 'fancybox-zoomOut'
            });
            location.href = '/';
        }
    })
}

function ae(elem) {
    let total, m, cl, pr;
    const b = document.querySelector('.button.button--primary');
    let buttonWeight = b.dataset.weight;
    let buttonSize = b.dataset.size;
    let buttonPrice = b.dataset.price;
    let buttonTotal = b.dataset.total;
    let buttonK = b.dataset.k;
    let buttonL = b.dataset.l;

    if(!buttonL) {
        buttonL = 0;
    } else {
        buttonL /= 1000;
    }

    console.log(buttonK);
    console.log(buttonL);

    let weight = $(elem).val();
    let sizeInput = $('.prod-order__input[name=size]');
    let totalDiv = $('.prod-order__input[name=total]');

    if (buttonK > 0) {
        // total = document.forms['basket_form'].elements['tonns'].value.replace(',', '.') / k;
        total = weight / buttonK;
        if (buttonL > 0) {
            if (total > 0) {
                if (total > 100 && buttonL == 11.7) {
                    m = Math.ceil(total);
                } else {
                    cl = Math.floor(total / l);
                    m = cl * l;
                    if (m != m - (m % 1)) m = m.toFixed(3) * 1;
                }
                // document.forms['basket_form'].elements['meters'].value = m;
                sizeInput.text(m);
            } else {
                sizeInput.text('');
            }
        } else {
            if (total < 1)
                pr = 1000;
            else if (total < 10)
                pr = 100;
            else if (total < 100)
                pr = 10;
            else if (total < 1000)
                pr = 1;
            else
                pr = 0.1;

            total = Math.ceil(total * pr);
            if (total == 0)
                // document.forms['basket_form'].elements['meters'].value = '';
                sizeInput.val('');
            else
                sizeInput.val(total / pr);
        }
    } else
        // document.forms['basket_form'].elements['meters'].value = '';
        sizeInput.val('');
}

function be(elem) {
    let total, k, l, cl, pr;
    const b = document.querySelector('.button.button--primary');
    let buttonWeight = b.dataset.weight;
    let buttonSize = b.dataset.size;
    let buttonPrice = b.dataset.price;
    let buttonTotal = b.dataset.total;
    let buttonK = b.dataset.k;
    let buttonL = b.dataset.l;

    if(buttonL !== 0) buttonL /= 1000;

    let size = $(elem).val();
    let weightInput = $('.prod-order__input[name=weight]');
    let totalDiv = $('.prod-order__input[name=total]');

    if (buttonK > 0) {
        // total = document.forms['basket_form'].elements['meters'].value.replace(',', '.');
        total = size;
        if (buttonL > 0) {
            total = total * buttonK;
            cl = Math.ceil(total / buttonL);
            total = cl * buttonL;
        } else {
            total = total * buttonK;
        }
        if (total < 1)
            pr = 1000;
        else if (total < 10)
            pr = 100;
        else if (total < 100)
            pr = 10;
        else if (total < 1000)
            pr = 1;
        else
            pr = 0.1;

        total = Math.ceil(total * pr);
        if (total == 0)
            // document.forms['basket_form'].elements['tonns'].value = ''
            weightInput.val('');
        else
            // document.forms['basket_form'].elements['tonns'].value = total / pr
            weightInput.val(total / pr);
    } else
        // document.forms['basket_form'].elements['tonns'].value = '';
        weightInput.val('');
}