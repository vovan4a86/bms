<?php namespace App\Http\Controllers;

use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\SearchIndex;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request as Request;
use Illuminate\Mail\Message;
use Mailer;
use Settings;
use Cart;
use Fanky\Admin\Models\Order as Order;
use Response;

class SearchController extends Controller {

    public $bread = [];

    public function __construct() {
        $this->bread[] = [
            'url'  => route('search'),
            'name' => 'Результаты поиска'
        ];
    }

	public function getIndex(Request $request) {
        \View::share('canonical', route('search'));
        $q = $request->get('q', '');

        if (!$q) {
            $items_ids = [];
        } else {
            $items_ids = SearchIndex::orWhere('name', 'LIKE', '%' . $q . '%')
                ->orderByDesc('updated_at')
                ->pluck('product_id')->all();
        }
        $items = Product::whereIn('id', $items_ids)
            ->paginate(Settings::get('search_per_page') ?? 10)
            ->appends(['q' => $q]); //Добавить параметры в строку запроса можно через метод appends().

        if ($request->ajax()) {
            $view_items = [];
            foreach ($items as $item) {
                $view_items[] = view('search.search_item', [
                    'item' => $item,
                ])->render();
            }

            return response()->json([
                'items'      => $view_items,
                'paginate'   => view('paginations.with_pages', [
                    'paginator' => $items
                ])->render()
            ]);
        }

        $filterSizes = Product::public()->whereIn('catalog_id', $items_ids)->distinct()->pluck( 'size')->all();
        $filterNames = Product::public()->whereIn('catalog_id', $items_ids)->distinct()->pluck( 'name')->all();

        return view('search.index', [
            'items'       => $items,
            'title'       => 'Результат поиска «' . $q . '»',
            'query'       =>  $q,
            'bread'       => $this->bread,
            'name'        => 'Поиск ' . $q,
            'keywords'    => 'Поиск',
            'description' => 'Поиск',
            'headerIsWhite' => true,
            'filterSizes' => $filterSizes,
            'filterNames' => $filterNames,
        ]);
	}

}
