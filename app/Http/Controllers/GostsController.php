<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Page;
//use Request;
use Illuminate\Http\Request;
use Settings;
use View;

class GostsController extends Controller {
	public $bread = [];
	protected $gosts_page;

	public function __construct() {
		$this->gosts_page = Page::whereAlias('gosts')
			->get()
			->first();
		$this->bread[] = [
			'url'  => $this->gosts_page['url'],
			'name' => $this->gosts_page['name']
		];
	}

	public function index(Request $request) {
		$page = $this->gosts_page;
        $page->setSeo();
        if (!$page)
			abort(404, 'Страница не найдена');
		$bread = $this->bread;
//        $items = News::orderBy('date', 'desc')
//            ->public()->paginate(Settings::get('news_per_page'));
        $items = null;

        //обработка ajax-обращений, в routes добавить POST метод(!)
//        if ($request->ajax()) {
//            $view_items = [];
//            foreach ($items as $item) {
//                //добавляем новые элементы
//                $view_items[] = view('news.list_item', [
//                    'item' => $item,
//                ])->render();
//            }
//
//            return [
//                'items'      => $view_items,
//                'paginate' => view('paginations.links_limit', ['paginator' => $items])->render()
//            ];
//        }

        if (count($request->query())) {
            View::share('canonical', $this->gosts_page->alias);
        }

        return view('gosts.index', [
            'bread' => $bread,
            'items' => $items,
            'text' => $page->text,
        ]);
	}

}
