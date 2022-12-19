<?php namespace Fanky\Admin\Models;

use App\Traits\HasH1;
use App\Traits\HasImage;
use App\Traits\HasSeo;
use App\Traits\OgGenerate;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Request;
use Settings;
use SiteHelper;
use Thumb;
use URL;

/**
 * @property HasMany|Collection $public_children
 * @property bool               $published
 * @property int                $id
 * @property string             $name
 * @mixin \Eloquent
 * @method static whereParentId(int|mixed $id)
 */
class Catalog extends Model {
	use HasImage, OgGenerate, HasH1, HasSeo;
	protected $table = 'catalogs';
	protected $_parents = [];
	protected $_has_children = null;
	private $_url;
	private $_disableEventUpdateSlug;
	private $_disableEventUpdatePublished;
	protected $guarded = ['id'];

	protected $casts = [
		'settings'     => 'array',
		'children_ids' => 'array',
	];

    const UPLOAD_URL = '/uploads/catalogs/';

	public static $thumbs = [
		1 => '257x168|fit', //admin
		2 => '166x121', //main page big
		3 => '194x137', //main page small
	];
	public static function boot() {
		parent::boot();

		self::saved(function (self $category){
			if($category->isDirty('alias') || $category->isDirty('parent_id')){
				if (!$category->_disableEventUpdateSlug){
					self::updateUrlRecurse($category);
				}
			}
			if($category->isDirty('published') && $category->published == 0){
				if (!$category->_disableEventUpdatePublished){
					self::updateDisablePublisedhRecurse($category);
				}
			}
		});
	}

    public static function getCatalogList($parent_id = 0, $lvl = 0) {
        $result = [];
        foreach (self::whereParentId($parent_id)->orderBy('order')->get() as $item) {
            $result[$item->id] = str_repeat('&nbsp;', $lvl * 3) . $item->name;
            $result = $result + self::getCatalogList($item->id, $lvl + 1);
        }

        return $result;
    }

	public function delete() {
		$this->deleteImage();
		foreach ($this->children as $product) {
			$product->delete();
		}
		foreach ($this->products as $product) {
			$product->delete();
		}

		parent::delete();
	}

	public function parent() {
		return $this->belongsTo('Fanky\Admin\Models\Catalog', 'parent_id');
	}

	public function children(): HasMany {
		return $this->hasMany('Fanky\Admin\Models\Catalog', 'parent_id');
	}

	public function public_children() {
		return $this->children()
			->where('published', 1)
			->orderBy('order');
	}

	public function products() {
		return $this->hasMany('Fanky\Admin\Models\Product', 'catalog_id');
	}

    public function params() {
        return $this->hasMany('Fanky\Admin\Models\CatalogParam', 'catalog_id')
            ->join('params', 'catalog_params.param_id', '=', 'params.id');
    }

    public function filters() {
        return $this->hasMany('Fanky\Admin\Models\CatalogFilter', 'catalog_id')
            ->join('params', 'catalog_filters.param_id', '=', 'params.id');
    }

    public function catalog_sub_show() {
        return $this->hasMany(CatalogSubShow::class)
            ->join('catalogs', 'catalog_sub_show.catalog_sub_show_id',  'catalogs.id');
    }

    public function catalog_filters() {
        return $this->belongsToMany(CatalogFilter::class, 'catalog_filters', 'catalog_id', 'param_id');
    }

    public function add_params() {
        return $this->hasMany(CatalogParam::class, 'catalog_id')
            ->join('params', 'catalog_params.param_id', '=', 'params.id');
    }

	public function public_products() {
		return $this->hasMany('Fanky\Admin\Models\Product', 'catalog_id')
			->public()->orderBy('order');
	}

	public function scopePublic($query) {
		return $query->where('published', 1);
	}

	public function scopeOnMain($query) {
		return $query->where('on_main', 1);
	}

	public function scopeMainMenu($query) {
		return $query->public()->where('parent_id', 0)->orderBy('order');
	}

	public function getUrlAttribute() {
		if ($this->_url) return $this->_url;
		$path = 'catalog/' . $this->slug;
		$current_city = SiteHelper::getCurrentCity();
		if ($current_city) {
			$path = $current_city->alias . '/' . ltrim($path, '/');
		}

		$this->_url = route('default', ['alias' => $path]);

		return $this->_url;
	}

	public function getIsActiveAttribute() {
		//берем или весь или часть адреса, для родительских страниц
		$url = substr(URL::current(), 0, strlen($this->getUrlAttribute()));

		return ($url == $this->getUrlAttribute());
	}

	public function siblings() {
		return self::whereParentId($this->parent_id);
	}

	public function getParents($with_self = false, $reverse = false) {
		$p = $this;
		$parents = [];
		if ($with_self) $parents[] = $p;
		if (!count($this->_parents) && $this->parent_id > 0) {
			$catalogs = self::getCatalogs();
			while ($p && $p->parent_id > 0) {
				$p = @$catalogs[$p->parent_id];
				$this->_parents[] = $p;
			}
		}
		$parents = array_merge($parents, $this->_parents);
		if ($reverse) {
			$parents = array_reverse($parents);
		}

		return $parents;
	}

	public static function getCatalogs() {
		$catalogs = Cache::get('catalogs', []);
		if (!$catalogs) {
			$catalog_arr = Catalog::all(['id', 'parent_id', 'name', 'alias', 'published']);
			foreach ($catalog_arr as $item) {
				$catalogs[$item->id] = $item;
			}
			Cache::add('catalogs', $catalogs, 1);
		}

		return $catalogs;
	}

    public function findRootCategory($catalog_id) {
        $cur_cat = Catalog::find($catalog_id);
        if($cur_cat->parent_id !== 0) {
            $this->findRootCategory($cur_cat->parent_id);
        }
        return $cur_cat;
    }

	/**
	 * @param string,string[] $path
	 *
	 * @return Catalog|null
	 */
	public static function getByPath($path): ?Catalog {
		if(is_array($path)){
			$path = implode('/', $path);
		}

		return self::query()->public()->whereSlug($path)->first();
	}

	public function getBread() {
		$bread = [];
		$bread[] = [
			'name' => $this->name,
			'url'  => $this->url,
		];
		$catalog = $this;
		while ($catalog = $catalog->parent) {
			$bread[] = [
				'name' => $catalog->name,
				'url'  => $catalog->url,
			];
		}
		$index_catalog = Page::getByPath(['catalog']);
		$bread[] = [
			'name' => $index_catalog->name,
			'url'  => $index_catalog->url,
		];
		return array_reverse($bread, true);
	}

	public function getPublicChildren() {
		return $this->children()->public()->whereOnMain(1)->orderBy('order')->get();
	}

	public function getAllPublicChildren() {
		return $this->children()->public()->orderBy('order')->get();
	}

    public function getMainCatalog() {
        return $this->public()->whereOnMain(1)->orderBy('order')->get();
    }

	public function getRecurseChildrenIds(self $parent = null) {
		if (!$parent) $parent = $this;
		$ids = self::query()->where('slug', 'like', $parent->slug . '%')
			->pluck('id')->all();

		return $ids;
	}

    public function getRecurseChildrenIdsInner(self $parent = null) {
        if (!$parent) $parent = $this;
        $ids = self::query()->where('slug', 'like', $parent->slug)
            ->pluck('id')->all();

        return $ids;
    }

	public function getRecurseProductsCount() {
//		$count = Cache::remember('product_count_' . $this->id, env('CACHE_TIME'), function () {
//			$ids = $this->getRecurseChildrenIds();
//			return Product::whereIn('catalog_id', $ids)->public()->count();
//		});
//		return $count;

        $ids = $this->getRecurseChildrenIds();
        return Product::whereIn('catalog_id', $ids)->public()->count();
	}

	public function getH1() {
		return $this->h1 ? $this->h1 : $this->name;
	}

	public function getHasChildrenAttribute() {
		if ($this->_has_children === null) {
			$this->_has_children = ($this->children()->public()->count()) ? true : false;
		}

		return $this->_has_children;
	}

	public function updateProductCount() {
		$ids = $this->getRecurseChildrenIds();
		$count = Product::whereIn('catalog_id', $ids)->public()->count();
		$this->update(['product_count' => $count]);
	}

	public function generateTitle() {
		if(!$this->title || $this->title == $this->name){ //Если авто
			$this->title = "{$this->name} купить";
		}
	}

	public function generateDescription() {
		if(!$this->description){
			$this->description = "Купить {$this->name}";
		}
	}

	public static function getRecurseCategory($parent_id) {
		$categories = Catalog::whereParentId($parent_id)->pluck('id')->all();
		if (!count($categories))
			return [];
		$result = $categories;
		foreach ($categories as $id) {
			$children = self::getRecurseCategory($id);
			if (count($children)) {
				$result = array_merge($result, $children);
			}
		}

		return $result;
	}

	/**
	 * @return Carbon
	 */
	public function getLastModifed() {
		/** @var Carbon $updated */
		$updated = $this->updated_at;
		$catalog_ids = self::getRecurseCategory($this->id);
		$catalog_ids[] = $this->id;
		$product_updated = Product::whereIn('catalog_id', $catalog_ids)->max('updated_at');
		if($product_updated){
			$product_updated = Carbon::createFromFormat("Y-m-d H:i:s", $product_updated, 'Asia/Yekaterinburg');
			return ($updated->gt($product_updated))? $updated: $product_updated;
		} else {
			return $updated;
		}
	}

	public static function updateUrlRecurse(self $category) {
		$parents = $category->getParents(true, true);
		$slug_arr = [];
		foreach ($parents as $parent){
			$slug_arr[] = $parent->alias;
		}
		//чтобы событие на обновление не сработало
		$category->_disableEventUpdateSlug = true;
		$category->update(['slug' => implode( '/', $slug_arr)]);
		foreach ($category->children()->get() as $child){
			self::updateUrlRecurse($child);
		}
	}

	public static function updateDisablePublisedhRecurse(self $category) {
		//чтобы событие на обновление не сработало
		$category->_disableEventUpdatePublished = true;
		$category->update(['published' => 0]);
		foreach ($category->children()->get() as $child){
			self::updateUrlRecurse($child);
		}
	}

    public static function getTopLevel() {
        return self::public()->whereParentId(0)->orderBy('order')->get();
    }

    public static function getTopLevelOnList() {
        return self::public()->whereParentId(0)->whereOnMainList(1)->orderBy('order')->get();
    }

    public function getProducts() {
        return $this->products()
            ->orderBy('order')
            ->with(['catalog', 'image', 'params_on_list'])
            ->get();
    }

    public function getRecurseProducts() {
        if($this->parent_id == 0) {
            $ids = self::getRecurseChildrenIds();
            return Product::public()->whereIn('catalog_id', $ids)
                ->orderBy('name', 'asc');
        } else {
            return $this->products();
        }
    }
}
