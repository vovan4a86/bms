import Scrollbar from '../plugins/smooth-scrollbar';

export const scrollbar = (selector, showTack) => {
  const citiesList = document.querySelector(selector);

  citiesList &&
    Scrollbar.init(citiesList, {
      alwaysShowTracks: showTack,
      dumping: 0.02
    });
};

scrollbar('.cities-list__content', true);
scrollbar('.overlay-nav__navigation', true);
scrollbar('.overlay-nav__content', true);
