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
use App\Classes\SiteHelper;
use Thumb;
use URL;

/**
 * @property HasMany|Collection $public_children
 * @property bool              $published
 * @property int              $id
 * @mixin \Eloquent
 * @method static whereParentId(int|mixed $id)
 */
class Action extends Model {
	use HasImage, OgGenerate, HasH1, HasSeo;
	protected $table = 'actions';
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

	const UPLOAD_URL = '/uploads/actions/';

	public static $thumbs = [
		1 => '257x168|fit', //admin
        2 => '410x255', //action catalog
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
    public static function getActionList($parent_id = 0, $lvl = 0) {
        $result = [];
        foreach (self::whereParentId($parent_id)->orderBy('order')->get() as $item) {
            $result[$item->id] = str_repeat('&nbsp;', $lvl * 3) . $item->name;
            $result = $result + self::getActionList($item->id, $lvl + 1);
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
		return $this->belongsTo('Fanky\Admin\Models\Action', 'parent_id');
	}

	public function children(): HasMany {
		return $this->hasMany('Fanky\Admin\Models\Action', 'parent_id');
	}

	public function public_children() {
		return $this->children()
			->where('published', 1)
			->orderBy('order');
	}

	public function action_products() {
		return $this->hasMany('Fanky\Admin\Models\ActionProduct', 'action_id');
	}

	public function public_products() {
		return $this->hasMany('Fanky\Admin\Models\ActionProduct', 'action_id')
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
		$path = 'action/' . $this->slug;
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
			$actions = self::getActions();
			while ($p && $p->parent_id > 0) {
				$p = @$actions[$p->parent_id];
				$this->_parents[] = $p;
			}
		}
		$parents = array_merge($parents, $this->_parents);
		if ($reverse) {
			$parents = array_reverse($parents);
		}

		return $parents;
	}

	public static function getActions() {
		$actions = Cache::get('actions', []);
		if (!$actions) {
			$action_arr = Action::all(['id', 'parent_id', 'name', 'alias', 'published']);
			foreach ($action_arr as $item) {
				$actions[$item->id] = $item;
			}
			Cache::add('actions', $actions, 1);
		}

		return $actions;
	}

	/**
	 * @param string,string[] $path
	 *
	 * @return Action|null
	 */
	public static function getByPath($path): ?Action {
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
		$action = $this;
		while ($action = $action->parent) {
			$bread[] = [
				'name' => $action->name,
				'url'  => $action->url,
			];
		}
		$index_action = Page::getByPath(['actions']);
		$bread[] = [
			'name' => $index_action->name,
			'url'  => $index_action->url,
		];
		return array_reverse($bread, true);
	}

	public function getPublicChildren() {
		return $this->children()->public()->whereOnMenu(1)->orderBy('order')->get();
	}

    public function getMainAction() {
        return $this->public()->whereOnMain(1)->orderBy('order')->get();
    }

	public function getPublicChildrenPublic() {
		return $this->children()->public()->orderBy('order')->get();
	}

	public function getRecurseChildrenIds(self $parent = null) {
		if (!$parent) $parent = $this;
		$ids = self::query()->where('slug', 'like', $parent->slug . '%')
			->pluck('id')->all();

		return $ids;
	}

	public function getRecurseProductsCount() {
		$count = Cache::remember('product_count_' . $this->id, env('CACHE_TIME'), function () {
			$ids = $this->getRecurseChildrenIds();

			return Product::whereIn('action_id', $ids)->public()->count();
		});

		return $count;
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
		$count = Product::whereIn('action_id', $ids)->public()->count();
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
		$categories = Action::whereParentId($parent_id)->pluck('id')->all();
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
		$action_ids = self::getRecurseCategory($this->id);
		$action_ids[] = $this->id;
		$product_updated = Product::whereIn('action_id', $action_ids)->max('updated_at');
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

    public function getProducts() {
        return $this->action_products()
            ->join('products', 'product_id', '=', 'products.id')
//            ->join('catalogs', 'product_id', '=', 'catalogs.id')
            ->select(
//                'catalogs.id as cat_id',
//                'catalogs.parent_id as cat_parent_id',
//                'catalogs.name as cat_name',
                'action_products.product_id',
                'products.catalog_id')
            ->get();
    }

    public static function fileSrc($file) {
        return self::UPLOAD_URL . $file;
    }

    public function dateFormat($format = 'd.m.Y') {
        if (!$this->date) return null;
        $date =  date($format, strtotime($this->date));
        $date = str_replace(array_keys(\App\Classes\SiteHelper::$monthRu),
            SiteHelper::$monthRu, $date);

        return $date;
    }

}
