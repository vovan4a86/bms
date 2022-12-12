<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Page;
//use Request;
use Illuminate\Http\Request;
use Settings;
use View;

class VacancyController extends Controller {
	public $bread = [];
	protected $vacancy_page;

	public function __construct() {
		$this->vacancy_page = Page::whereAlias('vacancy')
			->get()
			->first();
		$this->bread[] = [
			'url'  => $this->vacancy_page['url'],
			'name' => $this->vacancy_page['name']
		];
	}

	public function index(Request $request) {
		$page = $this->vacancy_page;
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
            View::share('canonical', $this->vacancy_page->alias);
        }

        return view('vacancy.index', [
            'bread' => $bread,
            'items' => $items,
            'text' => $page->text,
        ]);
	}

}
