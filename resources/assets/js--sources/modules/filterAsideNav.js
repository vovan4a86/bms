import $ from 'jquery';

export const filterAsideNav = () => {
  const $link = $('.aside-nav__link');
  const $subLink = $('.aside-nav__sublink');
  const $childSublink = $('.aside-nav__child-sublink');
  const cleanPath = window.location.origin + window.location.pathname;

  $subLink
    .filter('[href="' + cleanPath + '"]')
    .addClass('is-active')
    .closest('.aside-nav__sublist')
    .addClass('is-opened')
    .siblings('.aside-nav__link')
    .addClass('is-active');

  $childSublink
    .filter('[href="' + cleanPath + '"]')
    .addClass('is-active')
    .closest('.aside-nav__sublist')
    .addClass('is-opened')
    .siblings('.aside-nav__link')
    .addClass('is-active');

  $link
    .filter('[href="' + cleanPath + '"]')
    .addClass('is-active')
    .closest('.aside-nav__sublist')
    .addClass('is-opened');

  $link
    .filter('[href="' + cleanPath + '"]')
    .siblings('.aside-nav__sublist')
    .addClass('is-opened');
};

filterAsideNav();
