<?php namespace Fanky\Admin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Fanky\Admin\Models\Filter
 *
 * @property-read \Fanky\Admin\Models\Catalog $catalog
 * @property-read \Fanky\Admin\Models\Product $product
 * @mixin \Eloquent
 * @property int $id
 * @property int $product_id
 * @property int $catalog_id
 * @property string $name
 * @property string $value
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\Filter whereAlias($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\Filter whereCatalogId($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\Filter whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\Filter whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\Filter whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\Filter whereValue($value)
 */
class Filter extends Model {
	protected $guarded = ['id'];
	public $timestamps = false;

	public function product(){
		return $this->belongsTo(Product::class);
	}

	public function catalog(){
		return $this->belongsTo(Catalog::class);
	}
}
