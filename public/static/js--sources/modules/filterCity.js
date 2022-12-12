export const filterCity = () => {
  const input = document.querySelector('[data-search-city]');
  const cities = document.querySelectorAll('[data-city]');

  input && cities && input.addEventListener('input', searchCity);

  function searchCity() {
    setTimeout(() => {
      const search = input.value.toLowerCase();

      cities.forEach(city => {
        city.classList.add('v-hidden');

        search === '' && city.classList.remove('v-hidden');

        if (city.dataset.city.toLowerCase().includes(search)) {
          city.classList.remove('v-hidden');
        }
      });
    }, 250);
  }
};

filterCity();
