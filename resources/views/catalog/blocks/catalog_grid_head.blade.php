<div class="t-catalog__grid t-catalog__grid--head">
    <div class="t-catalog__col t-catalog__col--wide t-catalog__col--select">
        <!-- https://slimselectjs.com/options-->
        <!-- js--sources/modules/selects.js-->
        <select class="select" name="product" data-single-select multiple>
            <option data-placeholder="true">Продукция</option>
            @foreach($filterNames as $name)
                <option value="{{ $name }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <div class="t-catalog__col t-catalog__col--select">
        <select class="select" name="product" data-multi-select multiple>
            <option data-placeholder="true">Размер</option>
            @foreach($filterSizes as $size)
                <option value="{{ $size }}">{{ $size }}</option>
            @endforeach
        </select>
    </div>
    <div class="t-catalog__col t-catalog__col--wide">
        <div class="t-catalog__col-label">Марка</div>
    </div>
    <div class="t-catalog__col">
        <div class="t-catalog__col-label">Длина</div>
    </div>
    <div class="t-catalog__col t-catalog__col--wide">
        <div class="t-catalog__col-label">Цена, руб</div>
    </div>
</div>
