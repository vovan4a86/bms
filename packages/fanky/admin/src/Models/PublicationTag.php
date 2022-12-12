<?php namespace Fanky\Admin\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thumb;


/**
 * Fanky\Admin\Models\PublicationTag
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Fanky\Admin\Models\Publication[] $publications
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\PublicationTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\PublicationTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\PublicationTag query()
 * @mixin \Eloquent
 */
class PublicationTag extends Model {

	protected $guarded = ['id'];

	public function publications() {
		return $this->belongsToMany(Publication::class);
	}
}
