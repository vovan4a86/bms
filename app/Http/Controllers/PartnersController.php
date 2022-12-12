<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Page;
//use Request;
use Illuminate\Http\Request;
use Settings;
use View;

class PartnersController extends Controller {
	public $bread = [];
	protected $partners_page;

	public function __construct() {
		$this->partners_page = Page::whereAlias('partners')
			->get()
			->first();
		$this->bread[] = [
			'url'  => $this->partners_page['url'],
			'name' => $this->partners_page['name']
		];
	}

	public function index(Request $request) {
		$page = $this->partners_page;
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
            View::share('canonical', $this->partners_page->alias);
        }

        return view('partners.index', [
            'bread' => $bread,
            'items' => $items,
            'text' => $page->text,
        ]);
	}

}
