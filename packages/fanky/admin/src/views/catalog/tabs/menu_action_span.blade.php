<span class="action-link {{$action->style}}" title="{{$action->title}}">
        <img class="action-link__picture lazy entered loaded"
             src="{{ \Fanky\Admin\Models\Catalog::UPLOAD_URL . $action->image }}"
             data-src="{{ \Fanky\Admin\Models\Catalog::UPLOAD_URL . $action->image }}" alt="" width="153" height="161">
        <span class="action-link__title">{{$action->title}}</span>
        <span class="action-link__subtitle">{{$action->text}}</span>
        <span class="action-link__price">от&nbsp;
            <span class="action-link__current">{{$action->price}}</span>&nbsp;₽/{{$action->measure}}
        </span>
</span>
