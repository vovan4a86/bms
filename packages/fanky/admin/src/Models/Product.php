<?php namespace Fanky\Admin\Models;

use App\Traits\HasH1;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Settings;
use Thumb;
use Carbon\Carbon;

/**
 * Fanky\Admin\Models\Product
 *
 * @property int $id
 * @property int $catalog_id
 * @property string $name
 * @property string|null $text
 * @property int $price
 * @property int $raw_price
 * @property int $price_per_item
 * @property int $price_per_metr
 * @property int $price_per_kilo
 * @property int $price_per_m2
 * @property float $k
 * @property string $image
 * @property int $published
 * @property boolean $on_main
 * @property boolean $is_kit
 * @property int $order
 * @property string $alias
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Fanky\Admin\Models\Catalog $catalog
 * @property-read mixed $image_src
 * @property-read mixed $url
 * @property-read \Illuminate\Database\Eloquent\Collection|\Fanky\Admin\Models\ProductImage[] $images
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product onMain()
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product public ()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereOnMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product wherePriceUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product wherePublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Fanky\Admin\Models\Product withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $size
 * @property string|null $h1
 * @property string|null $price_name
 * @property string|null $og_title
 * @property string|null $warehouse
 * @property string|null $wall
 * @property string|null $characteristic
 * @property string|null $characteristic2
 * @property string|null $cutting
 * @property string|null $steel
 * @property string|null $length
 * @property string|null $gost
 * @property string|null $comment
 * @property float|null $weight
 * @property float|null $balance
 * @property string|null $og_description
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereCharacteristic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereCharacteristic2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereCutting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereGost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereH1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereOgDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereOgTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product wherePriceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereSteel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereWall($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Fanky\Admin\Models\Product whereWeight($value)
 */
class Product extends Model {
    use HasSeo, HasH1;

    protected $_parents = [];

    protected $guarded = ['id'];

    const UPLOAD_PATH = '/public/uploads/products/';
    const UPLOAD_URL = '/uploads/products/';

    const NO_IMAGE = "//static/images/common/no_image.png";

    public function catalog() {
        return $this->belongsTo(Catalog::class);
    }

    public function images(): HasMany {
        return $this->hasMany(ProductImage::class, 'product_id')
            ->orderBy('order');
    }

    public function image(): HasOne {
        return $this->hasOne(ProductImage::class, 'product_id')
            ->orderBy('order');
    }

    public function getImage($img) {
        return \Fanky\Admin\Models\ProductImage::UPLOAD_URL . $img;
    }

    public function getRootImage() {
        $category = Catalog::find($this->catalog_id);
        $root = $category;
        while ($root->parent_id !== 0) {
            $root = $root->findRootCategory($root->parent_id);
        }
        if ($root->image) {
            return \Fanky\Admin\Models\Catalog::UPLOAD_URL . $root->image;
        } else {
            return self::NO_IMAGE;
        }
    }

    public function params() {
        return $this->hasMany(ProductParam::class);
    }

    //related
    public function related() {
        return $this->hasMany(ProductRelated::class, 'product_id')
            ->join('products', 'product_related.related_id', '=', 'products.id');
    }

    public function params_on_list() {
        return $this->params()
            ->where('on_list', 1);
    }

    public function params_on_spec() {
        return $this->params()
            ->where('on_spec', 1);
    }

    public function scopePublic($query) {
        return $query->where('published', 1);
    }

    public function scopeIsAction($query) {
        return $query->where('is_action', 1);
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
        if (!$this->_url) {
            $this->_url = $this->catalog->url . '/' . $this->alias;
        }
        return $this->_url;
    }

    public function getParents($with_self = false, $reverse = false): array {
        $parents = [];
        if ($with_self) $parents[] = $this;
        $parents = array_merge($parents, $this->catalog->getParents(true));
        $parents = array_merge($parents, $this->_parents);
        if ($reverse) {
            $parents = array_reverse($parents);
        }

        return $parents;
    }

    public function delete() {
        foreach ($this->images as $image) {
            $image->delete();
        }

        parent::delete();
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
            'url' => $this->url,
            'name' => $this->name
        ];

        return $bread;
    }

    public function getFormatedPriceAttribute() {
        return number_format($this->price, 0, ',', ' ');
    }

    public static function getActionProducts() {
        return self::where('published', 1)->where('is_action', 1)->get();
    }

    public static function getPopularProducts() {
        return self::where('published', 1)->where('is_popular', 1)->get();
    }

    public function showCategoryImage($catalog_id) {
        $root = Catalog::find($catalog_id);
        while ($root->parent_id !== 0) {
            $root = $root->findRootCategory($root->parent_id);
        }
        return $root->thumb(2);
    }

    public static function findRootParentName($catalog_id) {
        $root = Catalog::find($catalog_id)->getParents();

        if (isset($root[0])) {
            return Catalog::find($root[0]['id'])->name;
        } else {
            return Catalog::find($catalog_id)->name;
        }
    }

    public function multiplyPrice($price) {
        $percent = $price * Settings::get('multiplier') / 100;
        return $price + $percent;
    }

    public static function fullPrice($price) {
        $percent = $price * Settings::get('multiplier') / 100;
        return $price + $percent;
    }

    public function getLength() {
        if ($this->length) {
            return $this->length;
        } elseif ($this->dlina) {
            return preg_replace('/[А-Яа-я]/', '', $this->dlina);
        } else {
            return null;
        }
    }

    public function showAnyImage() {
        $is_item_images = $this->images()->get();
        $root_image = $this->getRootImage() ?: self::NO_IMAGE;
        return count($is_item_images) ? \Fanky\Admin\Models\ProductImage::UPLOAD_URL . $is_item_images[0]->image :
            $root_image;
    }

    private function replaceTemplateVariable($template) {
        $name_parts = explode(' ', $this->name, 2);
        $replace = [
            '{name}' => $this->name,
            '{lower_name}' => Str::lower($this->name),
            '{gost}' => $this->gost,
            '{price}' => $this->price ?? 0,
            '{name_part1}' => array_get($name_parts, 0),
            '{name_part2}' => array_get($name_parts, 1),
            '{size}' => $this->size,
            '{wall}' => $this->wall,
            '{steel}' => $this->steel,
            '{measure}' => $this->measure,
            '{manufacturer}' => $this->manufacturer,
            '{length}' => $this->length,
            '{emails_for_order}' => $this->emails_for_order,
            '{product_article}' => $this->product_article,
        ];

        return str_replace(array_keys($replace), array_values($replace), $template);
    }

    public function getTitleTemplate($catalog_id = null) {
        if (!$catalog_id) $catalog_id = $this->catalog_id;
        $catalog = Catalog::find($catalog_id);
        if (!$catalog) return null;
        if (!empty($catalog->product_title_template)) return $catalog->product_title_template;
        if ($catalog->parent_id) return $this->getTitleTemplate($catalog->parent_id);

        return null;
    }

    public static $defaultTitleTemplate = '{name} купить{city} - БИЗНЕС-МС';

    public function generateTitle() {
        if (!($template = $this->getTitleTemplate())) {
            if ($this->title && $this->title != $this->name) {
                $template = $this->title;
            } else {
                $template = self::$defaultTitleTemplate;
            }
        }

        if (strpos($template, '{city}') === false) { //если кода city нет - добавляем
            $template .= '{city}';
        }
        $this->title = $this->replaceTemplateVariable($template);
    }

    public function getDescriptionTemplate($catalog_id = null) {
        if (!$catalog_id) $catalog_id = $this->catalog_id;
        $catalog = Catalog::find($catalog_id);
        if (!$catalog) return null;
        if (!empty($catalog->product_description_template)) return $catalog->product_description_template;
        if ($catalog->parent_id) return $this->getDescriptionTemplate($catalog->parent_id);

        return null;
    }

    public function getTextTemplate($catalog_id = null) {
        if (!$catalog_id) $catalog_id = $this->catalog_id;
        $catalog = Catalog::find($catalog_id);
        if (!$catalog) return null;
        if (!empty($catalog->product_text_template)) return $catalog->product_text_template;
        if ($catalog->parent_id) return $this->getTextTemplate($catalog->parent_id);

        return null;
    }

    public static $defaultDescriptionTemplate = '{name} купить{city} по цене от {price} руб. | БИЗНЕС-МС';

    public function generateDescription() {
        if (!($template = $this->getDescriptionTemplate())) {
            if (!$template && $this->description) {
                $template = $this->description;
            } else {
                $template = self::$defaultDescriptionTemplate;
            }
        }

        if (strpos($template, '{city}') === false) { //если кода city нет - добавляем
            $template .= '{city}';
        }

        $this->description = $this->replaceTemplateVariable($template);;
    }

    public function generateText() {
        $template = $this->getTextTemplate();
        if (!$template) {
            $template = $this->text;
        }

        $this->text = $this->replaceTemplateVariable($template);
    }

    public function generateKeywords() {
        if (!$this->keywords) {
            $this->keywords = mb_strtolower($this->name . ' цена, ' . $this->name . ' купить, ' . $this->name . '');
        }
    }

    public function getPricePerTonnAttribute() {
        return number_format($this->price, 0, '', ' ');
    }

    public function getRoundKAttribute() {
        if ($this->k) {
            $pr = 0;
            if ($this->k < 1)
                $pr = 1000;
            else if ($this->k < 10)
                $pr = 100;
            else if ($this->k < 100)
                $pr = 10;
            else if ($this->k < 1000)
                $pr = 1;
            else
                $pr = 0.1;

            $k = ceil($this->k * $pr);
            return $k / $pr;
        } else {
            return null;
        }
    }

    public function getAnyPrice(): ?string {
        if ($this->price) {
            return $this->price;
        } elseif ($this->price_per_item) {
            return $this->price_per_item;
        } elseif ($this->price_per_kilo) {
            return $this->price_per_kilo;
        } elseif ($this->price_per_metr) {
            return $this->price_per_metr;
        } elseif ($this->price_per_m2) {
            return $this->price_per_m2;
        } else {
            return null;
        }
    }

    public function getMeasurePrice(): ?string {
        if ($this->measure == 'т') {
            return $this->price;
        } elseif ($this->measure == 'шт') {
            return $this->price_per_item;
        } elseif ($this->measure == 'кг') {
            return $this->price_per_kilo;
        } elseif ($this->measure == 'м') {
            return $this->price_per_metr;
        } elseif ($this->measure == 'м2') {
            return $this->price_per_m2;
        } else {
            return null;
        }
    }

    public function getAnyMeasure(): ?string {
        if ($this->price) {
            return 'т';
        } elseif ($this->price_per_item) {
            return 'шт';
        } elseif ($this->price_per_kilo) {
            return 'кг';
        } elseif ($this->price_per_metr) {
            return 'м';
        } elseif ($this->price_per_m2) {
            return 'м2';
        } else {
            return null;
        }
    }

    public function getProductOrderView(): ?string {
        if ($this->price) {
            return 'catalog.blocks.product_order_t';
        } elseif ($this->price_per_item) {
            return 'catalog.blocks.product_order_item';
//        } elseif($this->price_per_kilo) {
//            return number_format($this->price_per_kilo, '0', '',' ');
//        } elseif($this->price_per_metr) {
//            return number_format($this->price_per_metr, '0', '',' ');
//        } elseif($this->price_per_m2) {
//            return number_format($this->price_per_m2, '0', '',' ');
        } else {
            return 'catalog.blocks.product_order_other';
        }
    }

}
