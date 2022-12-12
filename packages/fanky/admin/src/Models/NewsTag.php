<?php namespace Fanky\Admin\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thumb;


/**
 * Fanky\Admin\Models\NewsTag
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Fanky\Admin\Models\News[] $news
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\NewsTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\NewsTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\NewsTag query()
 * @mixin \Eloquent
 */
class NewsTag extends Model {

	protected $guarded = ['id'];

	public function news() {
		return $this->belongsToMany(News::class);
	}
}
