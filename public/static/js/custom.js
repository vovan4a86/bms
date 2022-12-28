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

function changeItem(elem) {
    const b = document.querySelector('.button.button--primary');
    let buttonWeight = b.dataset.weight;
    let buttonSize = b.dataset.size;
    let buttonPrice = b.dataset.price.replace(/ /g, '');
    let buttonTotal = b.dataset.total;
    let buttonK = b.dataset.k;
    let sizeInput = $('.prod-order__input[name=size]');
    let totalDiv = $('.prod-order__input[name=total]');

    let size = $(elem).val();

    b.dataset.size = size;
    b.dataset.total = new Intl.NumberFormat('ru-RU').format(size * buttonPrice);

    totalDiv.text(new Intl.NumberFormat('ru-RU').format(size * buttonPrice));
}

function changeItemPopup(elem) {
    let priceDiv = $('.prod-order__input[name=price]');
    let sizeDiv = $('.prod-order__input[name=size]');
    let totalDiv = $('.prod-order__input[name=total]');
    let price = $('[data-order-price]');
    let total = $('[data-order-total]');
    let size = $(elem).val();

    let res = size * price.text().replace(/ /g, '');

    total.text(new Intl.NumberFormat('ru-RU').format(res));
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
    let weight = 0
    if(form.weight) {
        weight = form.weight.value;
    }
    let size = 0;
    if(form.size) {
        size = form.size.value;
    }

    Cart.add(id, size, weight, function (res) {
        $('.basket').replaceWith(res.header_cart);
    }.bind(this));

    $('.is-close').click();
}

function addToCartProductItemPopup(form, e) {
    e.preventDefault()
    const id = form.id;
    let size = 0;
    if(form.size) {
        size = form.size.value;
    }

    Cart.add_pi(id, size, 0, function (res) {
        $('.basket').replaceWith(res.header_cart);
    }.bind(this));

    $('.is-close').click();
}

function addToCart(id) {
    Cart.add(id, 0, 1, function (res) {
        $('.basket').replaceWith(res.header_cart);
    }.bind(this));
}

function addToCartPerItem(id) {
    Cart.add_pi(id, 1, 0, function (res) {
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
    let buttonPrice = b.dataset.price;
    let buttonK = b.dataset.k;
    let buttonL = b.dataset.l;

    if(!buttonL) {
        buttonL = 0;
    } else {
        buttonL /= 1000;
    }

    let weight = $(elem).val();
    let weightInput = $(elem);
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
                    cl = Math.floor(total / buttonL);
                    m = cl * buttonL;
                    if (m != m - (m % 1)) m = m.toFixed(3) * 1;
                }
                sizeInput.val(m);
            } else {
                sizeInput.val('');
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
            if (total === 0)
                sizeInput.val('');
            else
                sizeInput.val(total / pr);
        }
    } else
        sizeInput.val('');

    b.dataset.weight = weightInput.val();
    b.dataset.size = sizeInput.val();
    b.dataset.total = Math.round(weightInput.val() * buttonPrice);

    totalDiv.text(Math.round(weightInput.val() * buttonPrice));

}

function be(elem) {
    let total, k, l, cl, pr;
    const b = document.querySelector('.button.button--primary');
    let buttonPrice = b.dataset.price;
    let buttonK = b.dataset.k;
    let buttonL = b.dataset.l;

    if(buttonL !== 0) buttonL /= 1000;

    let weightInput = $('.prod-order__input[name=weight]');
    let totalDiv = $('.prod-order__input[name=total]');

    if (buttonK > 0) {
        total = $(elem).val();

        if (buttonL > 0) {
            // total = total * buttonK;
            // cl = Math.ceil(total / buttonL);
            // total = cl * buttonL;
            cl = Math.ceil(total/buttonL);
            total = cl * buttonL;
            total = total * buttonK;

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
        if (total === 0)
            weightInput.val('');
        else
            weightInput.val(total / pr);
    } else
        weightInput.val('');

    b.dataset.weight = weightInput.val();
    b.dataset.size = $(elem).val();
    b.dataset.total = Math.round(weightInput.val() * buttonPrice);

    totalDiv.text(Math.round(weightInput.val() * buttonPrice));
}

function updateFilter(select, e) {
    e.preventDefault();
    let name = select.name;
    let list = $('.catalog-list');
    let products = $('.t-catalog__grid.t-catalog__grid--body');
    let container = $('.t-catalog');
    let paginate = $('.pagination');

    let data = $('#filter_form').serialize();
    let url = $('#filter_form').attr('action')

    sendAjax(url, data, function (json) {
        if(json.list !== 'undefined') {
            // list.remove();
            // container.append(json.list);
            products.remove();
            paginate.remove();
            for (let elem in json.list) {
                container.append(json.list[elem]);
            }
        }
        if(json.paginate !== 'undefined') {
            container.append(json.paginate);
        }
    });
}
