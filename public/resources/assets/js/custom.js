function changeWeight(elem) {
    const b = document.querySelector('.button.button--primary');
    let buttonWeight = b.dataset.weight;
    let buttonSize = b.dataset.size;
    let buttonPrice = b.dataset.price;
    let buttonTotal = b.dataset.total;

    buttonPrice = buttonPrice.replace(/ /g, '');

    let priceDiv = $('.prod-order__input[name=price]');
    let sizeDiv = $('.prod-order__input[name=size]');
    let totalDiv = $('.prod-order__input[name=total]');
    let weight = $(elem).val();
    let size = sizeDiv.val();

    let res = new Intl.NumberFormat('ru-RU').format(weight * buttonPrice)

    b.dataset.weight = weight;
    b.dataset.size = size;
    b.dataset.total = res;

    priceDiv.text(res);
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

    priceVal = price.text().replace(/ /g, '');

    let res = weight * +priceVal;

    total.text(new Intl.NumberFormat('ru-RU').format(res));
}

function showQ() {
    Fancybox.show([{ src: '#request-done', type: 'inline' }], {
        mainClass: 'popup--custom popup--complete',
        template: { closeButton: closeBtn },
        hideClass: 'fancybox-zoomOut'
    });
}

function cartUpdateCount(elem, id, price) {
    let count = $(elem).val();
    let cardSum = $('[data-id='+id+']');
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
    const qnt = form.weight.size;

    Cart.add(id, qnt, weight, function (res) {
        $('.basket').replaceWith(res.header_cart);
    }.bind(this));

    $('.is-close').click();
}

function addToCart(id) {
    Cart.add(id, 1, 1, function (res) {
        $('.basket').replaceWith(res.header_cart);
    }.bind(this));
}