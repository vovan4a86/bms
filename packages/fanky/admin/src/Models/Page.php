<?php namespace Fanky\Admin\Models;

use App\Traits\HasH1;
use App\Traits\HasImage;
use App\Traits\HasSeo;
use App\Traits\OgGenerate;
use Cache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use SiteHelper;
use URL;

class Page extends Model {
	use HasImage, OgGenerate, HasSeo, HasH1;
	const UPLOAD_URL = '/uploads/pages/';
	private $_disableEventUpdateSlug;
	private $_disableEventUpdatePublished;

	public static $thumbs = [
		1 => '100x100', //admin
		2 => '410x255', //service catalog
	];

	public static $page_classes = [
		24	=> 'btn--ico-w',
	];

	protected $table = 'pages';
	protected $_parents = [];
	private $_url;

    //страницы без региональности
	public static $excludeRegionAlias = [
		'ajax',
		'reviews',
        'about',
        'news',
		'publications',
		'policy',
		'cart',
		'search',
        'services',
        'actions',
        'contacts',
        'partners',
        'offers',
        'vacancy',
        'suppliers',
        'gosts',
        'answers',
        'faq',
	];

	private $regionPage = [
		'2', //catalog
	];

	//региональность пока только для каталога
	public static $regionAliases = [
		'catalog'
	];

	protected $guarded = ['id'];
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
	public function parent() {
		return $this->belongsTo('Fanky\Admin\Models\Page', 'parent_id');
	}

	public function children() {
		return $this->hasMany('Fanky\Admin\Models\Page', 'parent_id');
	}

	public function public_children() {
		return $this->children()
			->where('published', 1)
			->orderBy('order');
	}

	public function settingGroups() {
		return $this->hasMany('Fanky\Admin\Models\SettingGroup', 'page_id');
	}

	public function galleries() {
		return $this->hasMany('Fanky\Admin\Models\Gallery', 'page_id');
	}

	public function catalog() {
		return $this->hasOne('Fanky\Admin\Models\Catalog', 'page_id');
	}

	public function scopePublic($query) {
		return $query->where('published', 1);
	}

	public function scopeMain($query) {
		return $query->where('parent_id', 1);
	}

	public function scopeSubMenu($query) {
		return $query->where('parent_id', $this->id)->public()->orderBy('order');
	}

	public function getUrlAttribute(): string {
		if ($this->_url) return $this->_url;

		$path = [$this->alias];
		if (!count($this->_parents)) {
			$this->getParents();
		}
		foreach ($this->_parents as $parent) {
			$path[] = $parent->alias;
		}
		$path = implode('/', array_reverse($path));
		$current_city = SiteHelper::getCurrentCity();

		//региональность ко всем кроме self::$excludeRegionAlias
        if ($current_city && !in_array($this->alias, self::$excludeRegionAlias)) {
            $path = $current_city->alias . '/' . ltrim($path, '/');
        }

        //региональность только к self::$regionAliases (catalog)
//        if (!$current_city && !in_array($this->alias, self::$regionAliases)) {
//            $path = $current_city->alias . '/' . ltrim($path, '/');
//        }

        $this->_url = route('default', ['alias' => $path]);

		return $this->_url;
	}

	public function getIsActiveAttribute() {
		//берем или весь или часть адреса, для родительских страниц
		//исключение страница каталога
//		if ($this->alias == 'catalog') {
//			$url = URL::current();
//		} else {
//			$url = substr(URL::current(), 0, strlen($this->getUrlAttribute()));
//		}
		$url = URL::current();

		return ($url == $this->getUrlAttribute());
	}

	/**
	 * Братья/сестры
	 *
	 * @return mixed
	 */
	public function siblings() {
		return self::whereParentId($this->parent_id);
	}

	/**
	 * @param string[] $path
	 *
	 * @return Page
	 */
	public static function getByPath($path, $id = 1) {
		$parent_id = $id;
		$page = null;

		/* проверка по пути */
		foreach ($path as $alias) {
			$page = Page::whereAlias($alias)
				->whereParentId($parent_id)
				->public()
				->get(['id', 'alias', 'parent_id'])
				->first();
			if ($page === null) {
				return null;
			}
			$parent_id = $page->id;
		}

		if ($page && $page->id > 0) {
			return Page::find($page->id);
		} else {
			return null;
		}
	}

	/**
	 * @param bool $with_self
	 * @param bool $reverse
	 *
	 * @return array
	 */
	public function getParents($with_self = false, $reverse = false) {
		$p = $this;
		$parents = [];
		if ($with_self) $parents[] = $p;
		if (!count($this->_parents) && $this->parent_id > 1) {
			while ($p && $p->parent_id > 1) {
				$p = self::getPages($p->parent_id); // Page::find($p->parent_id, ['id','parent_id','name','alias','published']);
				$this->_parents[] = $p;
			}
		}
		$parents = array_merge($parents, $this->_parents);
		if ($reverse) {
			$parents = array_reverse($parents);
		}

		return $parents;
	}

	public static function getPages($id = null) {
		$pages = Cache::get('pages', []);
		if (!$pages) {
			$pages_arr = Page::all(['id', 'name', 'alias', 'published', 'parent_id']);
			foreach ($pages_arr as $item) {
				$pages[$item->id] = $item;
			}
			Cache::add('pages', $pages, 1);
		}
		if ($id) {
			return (isset($pages[$id])) ? $pages[$id] : null;
		} else {
			return $pages;
		}
	}

	public function getBread() {
		$bread = [];

		foreach ($this->getParents(true) as $p) {
			$bread[] = [
				'url'  => $p->url,
				'name' => $p->name
			];
		}

		return array_reverse($bread);
	}

	public function getPublicChildren() {
		return $this->children()->public()->orderBy('order')->get();
	}

    public function getPublicChildrenFooter() {
		return $this->children()->public()->where('on_footer_menu', 1)->orderBy('order')->get();
	}

	public function getAdditionalClassesAttribute() {
		return array_get(self::$page_classes, $this->id);
	}

	public function delete() {
		$this->deleteImage();
		foreach ($this->children as $child) {
			$child->delete();
		}

		parent::delete();
	}
	/**
	 * @return Carbon
	 */
	public function getLastModifed() {
		/** @var Carbon $updated */
		return $this->updated_at;
	}

    public function getView() {
        $view = 'pages.text';
        if (view()->exists('pages.unique.' . $this->alias)) {
            $view = 'pages.unique.' . $this->alias;
        }

        return $view;
    }
}
