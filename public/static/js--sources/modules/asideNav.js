import $ from 'jquery';

export const asideNav = () => {
  const $asideLink = $('.aside-nav__link');

  $asideLink.find('svg').on('click', function (e) {
    e.preventDefault();

    const $link = $(this).parent('.aside-nav__link');
    const $subList = $(this).parent('.aside-nav__link').siblings('.aside-nav__sublist');

    if ($link.length) {
      if ($subList.is(':visible')) {
        $subList.slideUp('fast');
        $link.removeClass('is-active');
      } else {
        closeAllMenus();

        $subList.slideDown('fast');
        $link.addClass('is-active');
      }
    }
  });

  function closeAllMenus() {
    $asideLink.each(function () {
      if ($(this).hasClass('is-active')) {
        $(this).removeClass('is-active');
        $(this).siblings('.aside-nav__sublist').slideUp('fast');
      }
    });
  }
};

asideNav();
