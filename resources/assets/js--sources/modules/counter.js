export const counter = () => {
  const counters = document.querySelectorAll('[data-counter]');

  counters &&
    counters.forEach(counter => {
      counter.addEventListener('click', function (e) {
        const input = this.querySelector('[data-count]');
        const target = e.target;
        // console.log(counter.dataset.id);

        if (target.closest('.counter__btn--prev') && input.value > 1) {
          input.value--;
          Cart.update(counter.dataset.id, input.value,  function(res) {
            if(res.cur_summ) {
              let summ = document.querySelector('.cart-table__col[data-id="' + counter.dataset.id + '"]');
              summ.innerHTML = res.cur_summ;
            }
            if(res.order_total) {
              let cart_aside = document.querySelector('.cart__aside');
              cart_aside.innerHTML = res.order_total;
            }
          })

        } else if (target.closest('.counter__btn--next')) {
          input.value++;
          Cart.update(counter.dataset.id, input.value,  function(res) {
            if(res.cur_summ) {
              let summ = document.querySelector('.cart-table__col[data-id="' + counter.dataset.id + '"]');
              summ.innerHTML = res.cur_summ;
            }
            if(res.order_total) {
              let cart_aside = document.querySelector('.cart__aside');
              cart_aside.innerHTML = res.order_total;
            }
          })
        }

        input.addEventListener('change', function () {
          if (this.value < 0 || this.value === '0' || this.value === '') {
            this.value = 1;
          }
        });
      });
    });
};

counter();
