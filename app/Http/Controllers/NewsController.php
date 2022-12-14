<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Page;
//use Request;
use Illuminate\Http\Request;
use Settings;
use View;

class NewsController extends Controller {
	public $bread = [];
	protected $news_page;

	public function __construct() {
		$this->news_page = Page::whereAlias('news')
			->get()
			->first();
		$this->bread[] = [
			'url'  => '/'.$this->news_page['alias'],
			'name' => $this->news_page['name']
		];
	}

	public function index(Request $request) {
		$page = $this->news_page;
		if (!$page)
			abort(404, 'Страница не найдена');
		$bread = $this->bread;
        $page->ogGenerate();
        $page->setSeo();
        $items = News::orderBy('date', 'desc')
            ->public()->paginate(Settings::get('news_per_page'));

        //обработка ajax-обращений, в routes добавить POST метод(!)
        if ($request->ajax()) {
            $view_items = [];
            foreach ($items as $item) {
                //добавляем новые элементы
                $view_items[] = view('news.list_item', [
                    'item' => $item,
                ])->render();
            }

            return [
                'items'      => $view_items,
                'paginate' => view('paginations.links_limit', ['paginator' => $items])->render()
            ];
        }

        if (count($request->query())) {
            View::share('canonical', $this->news_page->alias);
        }

        return view('news.index', [
            'title' => $page->title,
            'h1'    => $page->getH1(),
            'bread' => $bread,
            'items' => $items,
            'headerIsBlack' => true,
        ]);
	}

	public function item($alias) {
		$item = News::whereAlias($alias)->public()->first();
		if (!$item) abort(404);
		$bread = $this->bread;

		$bread[] = [
			'url'  => $item->url,
			'name' => $item->name
		];

		return view('news.item', [
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
