<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\City;
use Fanky\Admin\Models\Contact;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Page;
//use Request;
use Fanky\Admin\Models\Partner;
use Illuminate\Http\Request;
use Settings;
use View;

class ContactsController extends Controller {
	public $bread = [];
	protected $contacts_page;

	public function __construct() {
		$this->contacts_page = Page::whereAlias('contacts')
			->get()
			->first();
		$this->bread[] = [
			'url'  => $this->contacts_page['url'],
			'name' => $this->contacts_page['name']
		];
	}

	public function index(Request $request){
		$page = $this->contacts_page;
        $page->setSeo();

        if (!$page)
			abort(404, 'Страница не найдена');
		$bread = $this->bread;

        $contacts = Contact::orderBy('order')
            ->join('cities', 'cities.id', '=', 'contacts.city_id')
            ->select('contacts.*', 'cities.id as city_id', 'cities.name as city_name')
            ->get();
        $contactsCities = $contacts->groupBy('city_id');

        $cityLinks = collect();
        foreach ($contactsCities as $id => $city) {
            $cityLinks->push(City::find($id));
        }

        if (count($request->query())) {
            View::share('canonical', $this->contacts_page->alias);
        }

        return view('contacts.index', [
            'bread' => $bread,
            'contacts' => $contacts,
            'cityLinks' => $cityLinks,
            'h1' => $page->h1,
            'title' => $page->title,
            'text' => $page->text,
            'headerIsBlack' => true,
        ]);
	}

}
