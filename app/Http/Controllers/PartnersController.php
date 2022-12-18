<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Page;
//use Request;
use Fanky\Admin\Models\Partner;
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

        $partners = Partner::orderBy('order')->get();


        if (count($request->query())) {
            View::share('canonical', $this->partners_page->alias);
        }

        return view('partners.index', [
            'bread' => $bread,
            'partners' => $partners,
            'h1' => $page->h1,
            'title' => $page->title,
            'text' => $page->text,
            'headerIsBlack' => true,
        ]);
	}

}
