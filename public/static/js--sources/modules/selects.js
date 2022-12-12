import SlimSelect from '../plugins/slim-select/slimselect.min';

export const singleSelect = select => {
  new SlimSelect({
    select: select,
    showSearch: false,
    limit: 1
  });
};

const singleSelects = document.querySelectorAll('[data-single-select]');
singleSelects && singleSelects.forEach(select => singleSelect(select));

export const multiSelect = select => {
  new SlimSelect({
    select: select,
    showSearch: false,
    // searchPlaceholder: 'Найти:',
    // searchText: 'Не найдено',
    closeOnSelect: false,
    hideSelectedOption: true,
    limit: 3
  });
};

const multiSelects = document.querySelectorAll('[data-multi-select]');
multiSelects && multiSelects.forEach(select => multiSelect(select));
