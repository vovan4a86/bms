<?php namespace Fanky\Admin\Models;

use App\Classes\SiteHelper;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thumb;
use Carbon\Carbon;

/**
 * Fanky\Admin\Models\News
 *
 * @property int                 $id
 * @property int                 $published
 * @property int                 $city_id
 * @property string|null         $date
 * @property string              $name
 * @property int                 $price
 * @property string|null         $announce
 * @property string|null         $text
 * @property string              $alias
 * @property string              $title
 * @property string              $keywords
 * @property string              $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null         $deleted_at
 * @property-read mixed          $url
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\News onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News public ()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereAnnounce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\News withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\News withoutTrashed()
 * @mixin \Eloquent
 * @property string $h1
 * @property string|null $og_title
 * @property string|null $og_description
 * @property-read \Illuminate\Database\Eloquent\Collection|\Fanky\Admin\Models\NewsTag[] $tags
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereH1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereOgDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\News whereOgTitle($value)
 */
class Vacancy extends Model {

	use HasImage;

	protected $table = 'vacancies';

	protected $guarded = ['id'];

	public function city() {
		return $this->belongsTo(City::class);
	}

	public function scopePublic($query) {
		return $query->where('published', 1);
	}

	public function getUrlAttribute($value) {
		return route('news.item', ['alias' => $this->alias]);
	}

	public function dateFormat($format = 'd.m.Y') {
		if (!$this->date) return null;
		$date =  date($format, strtotime($this->date));
		$date = str_replace(array_keys(SiteHelper::$monthRu),
			SiteHelper::$monthRu, $date);

		return $date;
	}

    public function getPriceFormatAttribute() {
        return number_format($this->price, 0, '', ' ');
    }

	/**
	 * @return Carbon
	 */
	public function getLastModify() {
		return $this->updated_at;
	}

}
