import Swiper, { Autoplay, Pagination, Navigation, EffectFade, Lazy } from 'swiper';

const mainSlider = document.querySelector('[data-main-slider]');

function initMainSlider({ slider, sliderPagination, nextSlide, prevSlide }) {
  new Swiper(slider, {
    modules: [Autoplay, Pagination, Navigation, EffectFade, Lazy],
    fadeEffect: { crossFade: true },
    effect: 'fade',
    lazy: true,
    speed: 750,
    autoplay: {
      delay: 3500,
      disableOnInteraction: false
    },
    pagination: {
      el: sliderPagination,
      clickable: true
    },
    navigation: {
      prevEl: prevSlide,
      nextEl: nextSlide
    }
  });
}

mainSlider &&
  initMainSlider({
    slider: mainSlider,
    sliderPagination: '.hero__pagination',
    prevSlide: '[data-hero-prev]',
    nextSlide: '[data-hero-next]'
  });
