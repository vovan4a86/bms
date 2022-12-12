<?php namespace App\Http\Controllers;

use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request as Request;
use Illuminate\Mail\Message;
use Mailer;
use Settings;
use Cart;
use Fanky\Admin\Models\Order as Order;
use Response;

class SearchController extends Controller {

	public function getIndex(Request $request) {
        $data = $request->find;

        if(!$data) {
            abort(404, 'Страница не найдена');
        }

        $items = Product::public()->where('name', 'LIKE', "%{$data}%")
                    ->get();

        $page = Page::getByPath(['search']);
        $bread = $page->getBread();

        return view('search.index', [
            'bread' => $bread,
            'items' => $items,
            'data' => $data,
		]);
	}

}
