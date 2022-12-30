<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\City;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\SearchIndex;
use Fanky\Admin\Settings;
use SEOMeta;
use Request;

class PageController extends Controller {

    public function region_index($city_alias) {
        $this->city = City::current($city_alias);

        $page = $this->city->generateIndexPage();
        return $this->page($page);
    }

    public function region_page($alias) {
        return redirect(route('default', [$alias]), 301);

//		$this->city = City::current($city_alias);
//		$path = explode('/', $alias);
//		$page = Page::getByPath($path);
//
//		if(!$page){
//			abort(404, 'Страница не найдена (=^_^=)');
//		}
//		return $this->page($page);
    }

    public function page($alias = null) {
        $path = explode('/', $alias);
        if (!$alias) {
            $current_city = App::make('CurrentCity');
            $this->city = $current_city && $current_city->id ? $current_city : null;
            $page = $this->city->generateIndexPage();
        } else {
            $page = Page::getByPath($path);
            if (!$page) abort(404, 'Страница не найдена');
        }
        /** @var Page $page */
        $bread = $page->getBread();
        $children = $page->getPublicChildren();
        $page->h1 = $page->getH1();
        $page = $this->add_region_seo($page);
		$view = $page->getView();
		$page->ogGenerate();
        $page->setSeo();

        if($page->alias = 'about') {
            $about_image = Page::UPLOAD_URL . $page->image;
            $categories = Catalog::getTopOnMain();
            $headerIsBlack = true;
        }

		return response()->view($view, [
			'page'        => $page,
			'h1'          => $page->h1,
			'text'        => $page->text,
			'title'        => $page->title,
			'bread'       => $bread,
			'children'    => $children,
            'categories' => $categories ?? null,
            'about_image' => $about_image ?? null,
            'headerIsBlack' => $headerIsBlack ?? null,
		]);
	}

    public function search() {
        \View::share('canonical', route('search'));
        $q = Request::get('q', '');

        if (!$q) {
            $items_ids = [];
        } else {
            $items_ids = SearchIndex::orWhere('name', 'LIKE', '%' . $q . '%')
                ->orderByDesc('updated_at')
                ->pluck('product_id')->all();
        }
        $items = Product::whereIn('id', $items_ids)
            ->paginate(10)
            ->appends(['q' => $q]); //Добавить параметры в строку запроса можно через метод appends().

        if (Request::ajax()) {
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

        return view('search.index', [
            'items'       => $items,
            'title'       => 'Результат поиска «' . $q . '»',
            'query'       =>  $q,
            'name'        => 'Поиск ' . $q,
            'keywords'    => 'Поиск ',
            'description' => 'Поиск ',
            'headerIsWhite' => true,
        ]);
    }

    public function robots() {
		$robots = new App\Robots();
		if (App::isLocal()) {
			$robots->addUserAgent('*');
			$robots->addDisallow('/');
		} else {
			$robots->addUserAgent('*');
			$robots->addDisallow('/admin');
			$robots->addDisallow('/ajax');
		}

		$robots->addHost(env('BASE_URL'));
		$robots->addSitemap(url('sitemap.xml'));

		$response = response($robots->generate())
			->header('Content-Type', 'text/plain; charset=UTF-8');
		$response->header('Content-Length', strlen($response->getOriginalContent()));

		return $response;
	}
}
