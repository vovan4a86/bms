<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\City;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\Offer;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use SEOMeta;
use Settings;

class WelcomeController extends Controller {
    public function index() {
        City::current();
        /** @var Page $page */
        $page = Page::find(1);
        $page->ogGenerate();
        $page->setSeo();
//        $categories = Catalog::getTopLevelOnList();
//        $main_catalog = Catalog::find(1)->getTopLevelOnList();

//        $action_products = Product::getActionProducts();
//        $popular_products = Product::getPopularProducts();
//        $slider_news = News::getMainSliderNews();
        $offers = Offer::orderBy('date', 'desc')
            ->public()->paginate(Settings::get('offers_per_main'));

        return response()->view('pages.index', [
            'page'       => $page,
            'text'       => $page->text,
            'h1'         => $page->getH1(),
//            'categories' => $categories,
//            'main_catalog' => $main_catalog,
//            'action_products' => $action_products,
//            'popular_products' => $popular_products,
//            'slider_news' => $slider_news,
            'offers' => $offers ?? null,
        ]);
    }
}
