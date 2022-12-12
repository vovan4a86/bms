<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Publication;
use Fanky\Admin\Models\PublicationTag;
use Request;
use Settings;
use View;

class PublicationController extends Controller {
	public $bread = [];
	protected $pubs_page;

	public function __construct() {
		$this->pubs_page = Page::whereAlias('publications')
			->get()
			->first();
		$this->bread[] = [
			'url'  => '/'.$this->pubs_page->alias,
			'name' => $this->pubs_page->name
		];
	}

	public function index() {
		$page = $this->pubs_page;
		if (!$page)
			abort(404, 'Страница не найдена');
		$bread = $this->bread;
		$items = Publication::orderBy('date', 'desc')
			->public();
//			->with('tags');

//		if (Request::get('tag')) {
//			$items->whereHas('tags', function ($query) {
//				$query->whereId(Request::get('tag'));
//			});
//		}
		$items = $items->paginate(Settings::get('pubs_per_page'));
		$next_pubs_page = null;
		$next_pubs_count = 0;
		if ($items->hasMorePages()) {
			$next_pubs_page = $this->pubs_page->url . '?page=' . ($items->currentPage() + 1);
			//кол-во показанных элементов
			$show_items = $items->currentPage() * Settings::get('pubs_per_page');
			$next_pubs_count = ($items->total() - $show_items) < Settings::get('pubs_per_page') ?
				$items->total() - $show_items : Settings::get('pubs_per_page');
		}
		if (Request::ajax()) {
			$view_items = [];
			foreach ($items as $item) {
				$view_items[] = view('publications.list_item', ['item' => $item])->render();
			}

			return [
				'items'      => $view_items,
				'next_page'  => $next_pubs_page,
				'next_count' => $next_pubs_count,
			];
		}

		if ($p = Request::get('page')) {
			$page->title .= ' - Страница № ' . $p;
			$page->h1 .= ' - Страница № ' . $p;
			$page->description .= ' - Страница № ' . $p;
		}
		if (count(Request::query())) {
			View::share('canonical', $this->pubs_page->url);
		}
//		$tags = PublicationTag::whereHas('publications', function ($query) {
//			$query->where('publications.published', 1);
//		})->get();


		return view('publications.index', [
			'next_pubs_page'  => $next_pubs_page,
			'next_news_count' => $next_pubs_count,
			'items'           => $items,
//			'tags'            => $tags,
			'h1'              => $page->h1,
			'name'            => $page->name,
			'text'            => $page->text,
			'bread'           => $bread,
			'title'           => $page->title,
			'keywords'        => $page->keywords,
			'description'     => $page->description,
		]);
	}

	public function item($alias) {
		$item = Publication::whereAlias($alias)->public()->first();
		if (!$item) abort(404);
		$bread = $this->bread;
		$bread[] = [
			'url'  => $item->url,
			'name' => $item->name
		];

		$last = Publication::last(3);
//		$tags_ids = $item->tags()->pluck('id')->all();
		$relative = [];
//		if ($tags_ids) {
//			$relative = Publication::where('id', '!=', $item->id)->whereHas('tags', function ($query) use ($tags_ids) {
//				$query->whereIn('id', $tags_ids);
//			})->limit(5)->get();
//		}

		return view('publications.item', [
			'relative'    => $relative,
			'last'        => $last,
			'item'        => $item,
            'date'        => $item->dateFormat('d F Y'),
            'h1'          => $item->name,
			'name'        => $item->name,
			'text'        => $item->text,
			'bread'       => $bread,
			'title'       => $item->title,
			'keywords'    => $item->keywords,
			'description' => $item->description,
		]);
	}
}
