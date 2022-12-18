<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\City;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Page;
//use Request;
use Fanky\Admin\Models\Vacancy;
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
        $items = null;

        $uniqueCitiesIds = Vacancy::public()->groupBy('city_id')->pluck('city_id')->all();
        $cities = City::whereIn('id', $uniqueCitiesIds)->get();

        if (count($request->query())) {
            View::share('canonical', $this->vacancy_page->alias);
        }

        return view('vacancy.index', [
            'bread' => $bread,
            'items' => $items,
            'h1' => $page->h1,
            'title' => $page->title,
            'text' => $page->text,
            'cities' => $cities,
            'headerIsBlack' => true,
        ]);
	}

}
