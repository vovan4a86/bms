import { Fancybox } from '@fancyapps/ui';

export const closeBtn =
  '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M25 7 7 25M25 25 7 7"/></svg>';

const closeBtnSearch = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none"><path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M25 7 7 25M25 25 7 7"/></svg>`;

Fancybox.bind('[data-fancybox]', {
  closeButton: 'outside',
  hideClass: 'fancybox-zoomOut',
  infinite: false
});

Fancybox.bind('[data-popup]', {
  mainClass: 'popup--custom',
  template: { closeButton: closeBtn },
  hideClass: 'fancybox-zoomOut'
});

Fancybox.bind('[data-question]', {
  mainClass: 'popup--custom popup--question',
  template: { closeButton: closeBtn },
  hideClass: 'fancybox-zoomOut'
});

Fancybox.bind('[data-search-popup]', {
  template: { closeButton: closeBtnSearch },
  mainClass: 'popup--search',
  hideClass: 'fancybox-zoomOut',
  closeButton: 'outside'
});

Fancybox.bind('[data-create-order]', {
  mainClass: 'popup--custom popup--order',
  template: { closeButton: closeBtn },
  hideClass: 'fancybox-zoomOut',
  on: {
    reveal: (e, trigger) => {
      // отправка данных в попап из кнопки попапа
      const { title, weight, size, price, total } = trigger;
      const popup = trigger.$content;

      const popupTitle = popup.querySelector('[data-order-title]');
      const popupWeight = popup.querySelector('[data-order-weight]');
      const popupSize = popup.querySelector('[data-order-size]');
      const popupPrice = popup.querySelector('[data-order-price]');
      const popupTotal = popup.querySelector('[data-order-total]');

      if (popupTitle) popupTitle.textContent = title || '';
      if (popupWeight) popupWeight.value = weight || '';
      if (popupSize) popupSize.value = size || '';
      if (popupPrice) popupPrice.textContent = price || '';
      if (popupTotal) popupTotal.textContent = total || '';
    }
  }
});

export const showSuccessRequestDialog = () => {
  Fancybox.show([{ src: '#request-done', type: 'inline' }], {
    mainClass: 'popup--custom popup--complete',
    template: { closeButton: closeBtn },
    hideClass: 'fancybox-zoomOut'
  });
};

export const showSuccessOrderDialog = () => {
  Fancybox.show([{ src: '#order-done', type: 'inline' }], {
    mainClass: 'popup--custom popup--complete',
    template: { closeButton: closeBtn },
    hideClass: 'fancybox-zoomOut'
  });
};

// в свой модуль форм, импортируешь функцию вызова «спасибо» → вызываешь on success
// import { showSuccessRequestDialog } from 'пудо до компонента'
// import { showSuccessOrderDialog } from 'пудо до компонента'
// вызываешь где нужно
// showSuccessRequestDialog();
// showSuccessOrderDialog();
