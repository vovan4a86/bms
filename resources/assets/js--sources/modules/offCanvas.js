import HcOffCanvasNav from 'hc-offcanvas-nav';
import { Fancybox } from '@fancyapps/ui';
import { closeBtn } from './popups';

export const offCanvasNav = () => {
  const Nav = new HcOffCanvasNav('#mobile-nav', {
    customToggle: '.top-nav__catalog--mobile',
    navTitle: 'BMS',
    levelTitles: true,
    levelTitleAsBack: true,
    labelBack: 'Назад'
  });

  Nav.on('open', function () {
    const callbackBtn = document.querySelector('[data-open-callback]');

    if (callbackBtn) {
      callbackBtn.addEventListener(
        'click',
        function () {
          Fancybox.show(
            [
              {
                src: '#callback',
                type: 'inline'
              }
            ],
            {
              mainClass: 'popup--custom',
              template: { closeButton: closeBtn },
              hideClass: 'fancybox-zoomOut'
            }
          );

          Nav.close();
        },
        { once: true }
      );
    }
  });
};

offCanvasNav();
