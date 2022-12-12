<?php namespace Fanky\Admin\Models;

use App\Traits\HasH1;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thumb;
use Carbon\Carbon;

/**
 * Fanky\Admin\Models\Product
 *
 * @property int                                                                              $id
 * @property int                                                                              $catalog_id
 * @property string                                                                           $name
 * @property string|null                                                                      $text
 * @property float                                                                            $price
 * @property string                                                                            $formatedPrice
 * @property string                                                                           $image
 * @property int                                                                              $published
 * @property boolean                                                                          $on_main
 * @property boolean                                                                          $is_kit
 * @property int                                                                              $order
 * @property string                                                                           $alias
 * @property string                                                                           $title
 * @property string                                                                           $keywords
 * @property string                                                                           $description
 * @property \Carbon\Carbon|null                                                              $created_at
 * @property \Carbon\Carbon|null                                                              $updated_at
 * @property string|null                                                                      $deleted_at
 * @property-read \Fanky\Admin\Models\Catalog                                                 $catalog
 * @property-read mixed                                                                       $image_src
 * @property-read mixed                                                                       $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Fanky\Admin\Models\ActionProductImage[] $images
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct onMain()
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ActionProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct public ()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereOnMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct wherePriceUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ActionProduct withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\ActionProduct withoutTrashed()
 * @mixin \Eloquent
 * @property string|null                                                                      $size
 * @property string|null                                                                      $h1
 * @property string|null                                                                      $price_name
 * @property string|null                                                                      $og_title
 * @property string|null                                                                      $warehouse
 * @property string|null                                                                      $wall
 * @property string|null                                                                      $characteristic
 * @property string|null                                                                      $characteristic2
 * @property string|null                                                                      $cutting
 * @property string|null                                                                      $steel
 * @property string|null                                                                      $length
 * @property string|null                                                                      $gost
 * @property string|null                                                                      $comment
 * @property float|null                                                                       $weight
 * @property float|null                                                                       $balance
 * @property string|null                                                                      $og_description
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereCharacteristic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereCharacteristic2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereCutting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereGost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereH1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereOgDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereOgTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct wherePriceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereSteel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereWall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\ActionProduct whereWeight($value)
 */
class ActionProduct extends Model {
    use HasSeo, HasH1;

    protected $_parents = [];

    public $timestamps = FALSE;

    protected $guarded = ['id'];

    const UPLOAD_PATH = '/public/uploads/action-products/';
    const UPLOAD_URL  = '/uploads/action-products/';

    public function action() {
        return $this->belongsTo(Action::class);
    }

    public function scopePublic($query) {
        return $query->where('published', 1);
    }

    public function scopeInStock($query) {
        return $query->where('in_stock', 1);
    }

    public function scopeOnMain($query) {
        return $query->where('on_main', 1);
    }

    public function getImageSrcAttribute($value) {
        return ($this->image)
            ? $this->image->image_src
            : null;
    }

    public function thumb($thumb) {
        return ($this->image)
            ? $this->image->thumb($thumb)
            : null;
    }

    private $_url;

    public function getUrlAttribute() {
        if(!$this->_url) {
            $this->_url = $this->catalog->url . '/' . $this->alias;
        }
        return $this->_url;
    }

    public function getParents($with_self = false, $reverse = false): array {
        $parents = [];
        if($with_self) $parents[] = $this;
        $parents = array_merge($parents, $this->catalog->getParents(true));
        $parents = array_merge($parents, $this->_parents);
        if($reverse) {
            $parents = array_reverse($parents);
        }

        return $parents;
    }

    /**
     * @return Carbon
     */
    public function getLastModify() {
        return $this->updated_at;
    }

    public function getBread() {
        $bread = $this->catalog->getBread();
        $bread[] = [
            'url'  => $this->url,
            'name' => $this->name
        ];

        return $bread;
    }

    public function getFormatedPriceAttribute() {
        return number_format($this->price, 0, ',', ' ');
    }

    public static function getActionProducts() {
        return self::where('published',  1)->where('is_action',  1)->get();
    }

    public static function getPopularProducts() {
        return self::where('published',  1)->where('is_popular',  1)->get();
    }

    public function findRootParentName($catalog_id) {
        $root = Catalog::find($catalog_id)->getParents();

        if(isset($root[0])) {
            return Catalog::find($root[0]['id'])->name;
        } else {
            return Catalog::find($catalog_id)->name;
        }
    }

}
