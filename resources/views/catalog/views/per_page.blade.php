<form action="{{ $category->url }}">
    <div class="catalog-list__show">
        <span>Показывать</span>
        <select class="catalog-list__pages" name="pages">
            @foreach([10,20,30,40] as $val)
                <option value="{{ $val }}"
                        {{ $val == $per_page ? 'selected' : '' }}>{{ $val }}
                </option>
            @endforeach
        </select>
    </div>
</form>
