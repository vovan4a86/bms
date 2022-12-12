<?php namespace Fanky\Admin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Fanky\Admin\Models\ProductParam
 *
 * @property-read \Fanky\Admin\Models\Catalog $catalog
 * @property-read \Fanky\Admin\Models\Product $product
 * @mixin \Eloquent
 * @property int $id
 * @property int $product_id
 * @property int $catalog_id
 * @property string $name
 * @property string $value
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ProductParam whereCatalogId($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ProductParam whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ProductParam whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ProductParam whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ProductParam whereValue($value)
 */
class AddParam extends Model {
	protected $guarded = ['id'];
	public $timestamps = false;

	public function product(){
		return $this->belongsTo(Product::class);
	}

	public function catalog(){
		return $this->belongsTo(Catalog::class);
	}
}
