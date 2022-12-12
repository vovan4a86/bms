import Swiper, { Pagination, EffectFade, Lazy, Navigation } from 'swiper';

export const mainSlider = ({ slider, pagination }) => {
  new Swiper(slider, {
    modules: [Pagination, EffectFade, Lazy],
    fadeEffect: { crossFade: true },
    effect: 'fade',
    lazy: true,
    pagination: {
      el: pagination,
      clickable: true
    }
  });
};
