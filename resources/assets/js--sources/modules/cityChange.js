export const cityChange = () => {
  const cityDialog = document.querySelector('.cities-dialog');
  const confirmCity = document.querySelector('[data-city-confirm]');
  const changeCity = document.querySelectorAll('[data-city-change]');
  const citiesList = document.querySelector('.cities-list');

  if (confirmCity && cityDialog) {
    confirmCity.addEventListener('click', function () {
      cityDialog.classList.add('is-hidden');
    });
  }

  if (changeCity) {
    changeCity.forEach(button =>
      button.addEventListener('click', function () {
        cityDialog.classList.add('is-hidden');
        citiesList.classList.add('is-visible');
      })
    );

    document.body.addEventListener('keydown', function (e) {
      e.code === 'Escape' && citiesList.classList.remove('is-visible');
    });

    document.body.addEventListener('click', function (e) {
      !e.target.closest('.cities') && citiesList.classList.remove('is-visible');
    });
  }
};

cityChange();
