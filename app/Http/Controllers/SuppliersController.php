<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Page;
//use Request;
use Illuminate\Http\Request;
use Settings;
use View;

class SuppliersController extends Controller {
	public $bread = [];
	protected $suppliers_page;

	public function __construct() {
		$this->suppliers_page = Page::whereAlias('suppliers')
			->get()
			->first();
		$this->bread[] = [
			'url'  => $this->suppliers_page['url'],
			'name' => $this->suppliers_page['name']
		];
	}

	public function index(Request $request) {
		$page = $this->suppliers_page;
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
            View::share('canonical', $this->suppliers_page->alias);
        }

        return view('suppliers.index', [
            'bread' => $bread,
            'items' => $items,
            'text' => $page->text,
        ]);
	}

}
