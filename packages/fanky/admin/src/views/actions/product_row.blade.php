<tr id="prod{{ $product->id }}">
    <td>{{ $product->name }}</td>
    <td>{{ $product->price }}</td>
    <td>{{ $product->catalog_id }}</td>
    <td>
        <a href="{{ route('admin.actions.del_action_product', [$product->id]) }}"
           class="btn btn-default" onclick="delActionProduct(this, event)">
            <i class="fa fa-trash text-red"></i></a>
    </td>
</tr>