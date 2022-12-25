<?php namespace Fanky\Admin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Fanky\Admin\Models\ProductParam
 *
 * @property-read \Fanky\Admin\Models\Product $product
 * @mixin \Eloquent
 * @property int $id
 * @property int $city_id
 * @property string $title
 * @property string $phone1
 * @property string $phone1_comment
 * @property string $phone2
 * @property string $phone2_comment
 * @property string $email
 * @property string $work_days
 * @property float  $lat
 * @property float  $long
 * @property string  $whatsapp
 * @property string  $skype
 * @property string  $telegram
 * @property int    $order
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ProductParam whereCatalogId($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ProductParam whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ProductParam whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ProductParam whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ProductParam whereValue($value)
 */
class Contact extends Model {
	protected $guarded = ['id'];
	public $timestamps = false;

	public function city(){
		return $this->belongsTo(City::class);
	}

}
