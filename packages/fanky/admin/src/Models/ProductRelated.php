<?php namespace Fanky\Admin\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thumb;

/**
 * Fanky\Admin\Models\ProductImage
 *
 * @property int        $id
 * @property int        $product_id
 * @property string     $image
 * @property int        $order
 * @property-read mixed $src
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage whereProductId($value)
 * @mixin \Eloquent
 * @property-read mixed $image_src
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage query()
 */
class ProductRelated extends Model {

	protected $table = 'product_related';

	protected $guarded = ['id'];

	public $timestamps = false;

	public function product(): BelongsTo {
	    return $this->belongsTo(Product::class);
    }

    public function getAnyPrice(): ?string {
        if($this->price) {
            return number_format($this->price, 0, '', ' ');
        } elseif($this->price_per_item) {
            return number_format($this->price_per_item, 0, '', ' ');
        } elseif($this->price_per_kilo) {
            return number_format($this->price_per_kilo, 0, '', ' ');
        } elseif($this->price_per_metr) {
            return number_format($this->price_per_metr, 0, '', ' ');
        } elseif($this->price_per_m2) {
            return number_format($this->price_per_m2, 0, '', ' ');
        } else {
            return null;
        }
    }
}
