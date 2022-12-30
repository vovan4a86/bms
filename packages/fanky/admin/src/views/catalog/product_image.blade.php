<span class="images_item">
	<img class="img-polaroid" src="{{ $image->image }}"
		 style="cursor:pointer;" data-image="{{ $image->image }}" onclick="popupImage('{{ $image->src }}')">
	<a class="images_del" href="{{ route('admin.catalog.productImageDel', [$image->id]) }}"
	   onclick="return productImageDel(this)">
		<span class="glyphicon glyphicon-trash"></span>
	</a>
</span>
