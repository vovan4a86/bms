<?php namespace Fanky\Admin\Models;

use App\Traits\HasFile;
use App\Traits\HasImage;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Thumb;

/**
 * Fanky\Admin\Models\ProductImage
 *
 * @property int        $id
 * @property int        $gost_id
 * @property string     $name
 * @property string     $description
 * @property string     $file
 * @property int        $order
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage whereGostId($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ProductImage query()
 */
class GostFile extends Model {

    use HasFile;

	protected $guarded = ['id'];

	public $timestamps = false;

	const UPLOAD_URL = '/uploads/gost_files/';

    public function gost() {
        return $this->belongsTo(Page::class);
    }

}
