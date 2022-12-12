<?php namespace Fanky\Admin\Models;

use App\Classes\SiteHelper;
use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thumb;

/**
 * Fanky\Admin\Models\Publication
 *
 * @property int $id
 * @property int $published
 * @property string|null $date
 * @property string $name
 * @property string $h1
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $announce
 * @property string|null $text
 * @property string $image
 * @property string $alias
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read mixed $image_src
 * @property-read mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Fanky\Admin\Models\PublicationTag[] $tags
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication public()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereAnnounce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereH1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereOgDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereOgTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Publication whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Publication extends Model {

	use HasImage;

//	protected $fillable = ['name', 'image', 'published', 'date', 'announce', 'text', 'alias', 'title', 'keywords', 'description'];
	protected $guarded = ['id'];

	const UPLOAD_URL = '/uploads/publications/';

	public static $thumbs = [
		1 => '100x50', //admin
		2 => '400x190|fit', //news_list
		3 => '715x408', //news page
		4 => '211x199|fit', //index page
	];

//	public function tags() {
//		return $this->belongsToMany(PublicationTag::class);
//	}

	public function scopePublic($query) {
		return $query->where('published', 1);
	}

	public function getUrlAttribute() {
		return route('publications.item', ['alias' => $this->alias]);
	}

	public function dateFormat($format = 'd.m.Y') {
		if (!$this->date) return null;
		$date =  date($format, strtotime($this->date));
		$date = str_replace(array_keys(SiteHelper::$monthRu),
			SiteHelper::$monthRu, $date);

		return $date;
	}

	public static function last($count = 3) {
		$items = self::orderBy('date', 'desc')->public()->limit($count)->get();

		return $items;
	}
}
