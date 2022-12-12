<?php
namespace Fanky\Admin\Models;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;

/**
 * Fanky\Admin\Models\Hardware
 *
 * @property-read \Fanky\Admin\Models\HardwareCategory $category
 * @property-read mixed $image_src
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Hardware newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Hardware newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Hardware public()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Hardware query()
 * @mixin \Eloquent
 */
class Hardware extends Model {
	use HasImage;
	protected $guarded = ['id'];

	const UPLOAD_URL = '/uploads/hardware/';

	public static $thumbs = [
		1 => '100x50', //admin
		2 => '142x142', //news_list
	];

	public function category() {
		return $this->belongsTo(HardwareCategory::class, 'category_id');
	}

	public function scopePublic($query) {
		return $query->where('published', 1);
	}

	public function delete() {
		$this->deleteImage();
		parent::delete();
	}
}