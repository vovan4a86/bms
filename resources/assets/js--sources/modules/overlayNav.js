import $ from 'jquery';
import { getScrollbarWidth } from '../functions/scrollBarWidth';

export const overlayNav = () => {
  const $body = $('body');
  const $header = $('.header');
  const $logo = $('.header__logo');
  const $logoDark = $logo.data('dark');
  const $logoLight = $logo.data('white');
  const $nav = $('.overlay-nav');
  const $trigger = $('[data-open-catalog]');

  let overlayIsOpen = false;

  $trigger.on('click', function () {
    overlayIsOpen ? closeOverlay() : openOverlay();
  });

  $('.overlay-nav__backdrop').on('click', closeOverlay);

  function openOverlay() {
    $header.addClass('is-active');
    $nav.addClass('is-active');
    $trigger.addClass('is-active');
    $trigger.find('span').text('Закрыть каталог');

    !$header.hasClass('header--home') && setNoScroll();

    overlayIsOpen = true;

    if (!$header.hasClass('header--white')) {
      !$header.hasClass('is-dark') && setLogo($logoDark);
    }
  }

  function closeOverlay() {
    $header.removeClass('is-active');
    $nav.removeClass('is-active');
    $trigger.removeClass('is-active');
    $trigger.find('span').text('Каталог товаров');
    $('body').removeClass('no-scroll');

    overlayIsOpen = false;

    !$header.hasClass('header--home') && removeNoScroll();

    if (!$header.hasClass('header--white')) {
      !$header.hasClass('is-dark') && setLogo($logoLight);
    }
  }

  function setLogo(logo) {
    $logo.css('background-image', `url("${logo}")`);
  }

  function setNoScroll() {
    $body.css('padding-right', getScrollbarWidth() + 'px');
    $body.addClass('no-scroll');
  }

  function removeNoScroll() {
    $body.css('padding-right', '');
    $body.removeClass('no-scroll');
  }
};

overlayNav();
