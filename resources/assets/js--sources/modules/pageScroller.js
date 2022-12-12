import $ from 'jquery';
import Swiper, { Lazy, Mousewheel, Pagination } from 'swiper';
import { isTouchDeviceType } from '../functions/isTouchDeviceType';
import { isSmallScreen } from '../functions/isSmallScreen';

const pageScroller = document.querySelector('[data-page-scroller]');

function initPageScroller({ scroller, initTransition }) {
  const transition = initTransition;

  const pageScroller = new Swiper(scroller, {
    modules: [Mousewheel, Pagination, Lazy],
    direction: 'vertical',
    slidesPerView: 'auto',
    spaceBetween: 0,
    mousewheel: true,
    autoHeight: true,
    speed: transition,
    allowTouchMove: isTouchDeviceType(),
    freeMode: !isSmallScreen('500px'),
    // change header colors by slides data-attributes
    on: {
      runCallbacksOnInit: true,
      init: function () {
        const slideBackground = this.slides[this.activeIndex].dataset.background;
        setHeaderColor(slideBackground, this.activeIndex);

        $('body').on('keydown', function (e) {
          if (e.keyCode === 38) {
            pageScroller.slidePrev();
          } else if (e.keyCode === 40) {
            pageScroller.slideNext();
          }
        });
      },
      slideNextTransitionStart: function () {
        const slideBackground = this.slides[this.activeIndex].dataset.background;
        setHeaderColor(slideBackground, this.activeIndex, this.slides.length - 1);
      },
      slidePrevTransitionStart: function () {
        const slideBackground = this.slides[this.activeIndex].dataset.background;
        setHeaderColor(slideBackground, this.activeIndex, this.slides.length - 1);
      }
    },
    breakpoints: {
      '@0.00': {
        slidesPerView: 'auto'
      },
      '@0.75': {
        slidesPerView: 'auto'
      },
      '@1.00': {
        slidesPerView: 1
      },
      '@1.50': {
        slidesPerView: 1
      }
    }
  });

  // change header colors by slides data-attributes
  function setHeaderColor(currentColor, currentSlide, count) {
    const $header = $('.header');
    const $headerTop = $('.header__top');
    const $headerLogo = $('.header__logo');
    const $logoDark = $headerLogo.data('dark');
    const $logoLight = $headerLogo.data('white');

    hideHeaderOnFooterIsVisible(count, currentSlide);

    setTimeout(() => {
      setHeaderColor(currentColor);
    }, transition / 2);

    function setHeaderColor(state) {
      manageHeader(state, currentSlide);
    }

    function manageHeader(state, currentView) {
      switch (state) {
        case 'light':
          $header.addClass('is-dark');
          $logoDark && setLogo($logoDark);
          break;
        case 'dark':
          $header.removeClass('is-dark');
          $logoLight && setLogo($logoLight);
          break;
      }

      if (currentView > 0) {
        $header.addClass('bordered');
        $headerTop.slideUp('fast');
      } else {
        $header.removeClass('bordered');
        $headerTop.slideDown('fast');
      }
    }

    function setLogo(logo) {
      $headerLogo.css('background-image', `url("${logo}")`);
    }

    function hideHeaderOnFooterIsVisible(count, currentSlide) {
      if (currentSlide === count) {
        setTimeout(() => $header.fadeOut('fast'), transition / 2);
      } else {
        setTimeout(() => $header.fadeIn('fast'), transition / 2);
      }
    }
  }
}

pageScroller &&
  initPageScroller({
    scroller: pageScroller,
    initTransition: 1200
  });
