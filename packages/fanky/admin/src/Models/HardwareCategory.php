<?php
namespace Fanky\Admin\Models;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;

/**
 * Fanky\Admin\Models\HardwareCategory
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Fanky\Admin\Models\HardwareCategory[] $children
 * @property-read mixed $image_src
 * @property-read \Illuminate\Database\Eloquent\Collection|\Fanky\Admin\Models\Hardware[] $items
 * @property-read \Fanky\Admin\Models\HardwareCategory $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\HardwareCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\HardwareCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\HardwareCategory public()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\HardwareCategory query()
 * @mixin \Eloquent
 */
class HardwareCategory extends Model {
	use HasImage;
	const UPLOAD_URL = '/uploads/hardware_category/';
	protected $guarded = ['id'];

	public static $thumbs = [
		1 => '100x50', //admin
		2 => '142x142', //news_list
	];

	public function parent() {
		return $this->belongsTo(self::class, 'parent_id');
	}

	public function children() {
		return $this->hasMany(self::class, 'parent_id');
	}

	public function items() {
		return $this->hasMany(Hardware::class, 'category_id');
	}

	public function scopePublic($query) {
		return $query->where('published', 1);
	}

	public function delete() {

		foreach ($this->items as $item){
			$item->delete();
		}

		foreach ($this->children as $child){
			$child->delete();
		}
		$this->deleteImage();
		parent::delete();
	}

}