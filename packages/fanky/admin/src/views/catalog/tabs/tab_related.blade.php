<div class="tab-pane" id="tab_related">
    @if(!$product->id)
        <div>Добавление рекомендуемых товаров доступно только после сохранения товара</div>
    @else
        <table class="table table-hover table-condensed" id="related_list">
            <thead>
            <tr>
                <th>Название</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($product->related as $related)
                @include('admin::catalog.related_row', ['related' => $related])
            @endforeach
            </tbody>
        </table>

        <div class="form-group row">
            <div class="col-md-4">
                @if(count($product_list))
                    <select name="rel-name" class="form-control">
                        @foreach($product_list as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.catalog.add_related', $product->id) }}"
                   onclick="addRelated(this, event)" class="btn btn-primary add-rel">
                    Добавить товар</a>
            </div>
        </div>
    @endif
</div>
