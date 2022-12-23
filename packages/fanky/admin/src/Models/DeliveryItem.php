<?php namespace Fanky\Admin\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Thumb;

/**
 * Fanky\Admin\Models\ProductImage
 *
 * @property int        $id
 * @property string     $name
 * @property string     $description
 * @property string     $text
 * @property string     $price
 * @property string     $free
 * @property int        $order
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage whereOrder($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage query()
 */
class DeliveryItem extends Model {

	protected $fillable = ['name', 'description', 'text', 'price', 'free', 'order'];

	public $timestamps = false;

	public function order() {
	    $this->belongsTo(Order::class);
    }

}
