<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Offer;
use Fanky\Admin\Models\Page;
//use Request;
use Illuminate\Http\Request;
use Settings;
use View;

class OfferController extends Controller {
	public $bread = [];
	protected $offer_page;

	public function __construct() {
		$this->offer_page = Page::whereAlias('offers')
			->get()
			->first();
		$this->bread[] = [
			'url'  => $this->offer_page['url'],
			'name' => $this->offer_page['name']
		];
	}

	public function index(Request $request) {
		$page = $this->offer_page;
		if (!$page)
			abort(404, 'Страница не найдена');
		$bread = $this->bread;
        $items = Offer::orderBy('date', 'desc')
            ->public()->get();

        //обработка ajax-обращений, в routes добавить POST метод(!)
//        if ($request->ajax()) {
//            $view_items = [];
//            foreach ($items as $item) {
//                //добавляем новые элементы
//                $view_items[] = view('offers.list_item', [
//                    'item' => $item,
//                ])->render();
//            }
//
//            return [
//                'items'      => $view_items,
//                'paginate' => view('paginations.links_limit_offer', ['paginator' => $items])->render()
//            ];
//        }

        if (count($request->query())) {
            View::share('canonical', $this->offer_page->alias);
        }

        return view('offers.index', [
            'bread' => $bread,
            'items' => $items,
        ]);
	}

	public function item($alias) {
		$item = Offer::whereAlias($alias)->public()->first();
		if (!$item) abort(404);
		$bread = $this->bread;

		$bread[] = [
			'url'  => $item->url,
			'name' => $item->name
		];

		return view('offers.item', [
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
