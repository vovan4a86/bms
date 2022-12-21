<?php namespace App\Http\Controllers;

use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\MaterialImage;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use SEOMeta;
use View;
use Request;

class ServiceController extends Controller {

    public function index() {
        $page = Page::getByPath(['services']);
        if(!$page) return abort(404);
        $bread = $page->getBread();
        $page->h1 = $page->getH1();
//        $page = $this->add_region_seo($page);
        $page->setSeo();
        $categories = $page->getPublicChildren();

        return view('services.index', [
            'h1'         => $page->h1,
            'text'       => $page->text,
            'title'      => $page->title,
            'bread'      => $bread,
            'categories' => $categories,
            'headerIsWhite' => true,
        ]);
    }

    public function view($alias) {
        $path = explode('/', $alias);
        /* проверка на продукт в категории */
        $product = null;
        $end = array_pop($path);
        $category = Page::getByPath($path);
        if($category && $category->published) {
            $product = Product::whereAlias($end)
                ->public()
                ->whereCatalogId($category->id)->first();
        }
        if($product) {
            return $this->product($product);
        } else {
            array_push($path, $end);

            return $this->category($path + [$end]);
        }
    }

    public function category($path) {
        /** @var Page $category */
        $category = Page::getByPath($path, 3);
        if(!$category || !$category->published) abort(404, 'Страница не найдена');
        $bread = $category->getBread();
//        $category = $this->add_region_seo($category);
        $children = $category->public_children;
        $category->setSeo();

        return view('services.category', [
            'bread'    => $bread,
            'category' => $category,
            'children' => $children,
            'text' => $category->text,
            'h1'       => $category->getH1(),
        ]);
    }

    public function product(Product $product) {
        $bread = $product->getBread();
//        $product = $this->add_region_seo($product);
        $product->setSeo();
        $params = $product->params()
            ->where('group', '!=', '')
            ->get()
            ->groupBy('group');

        return view('catalog.product', [
            'product'    => $product,
            'bread'      => $bread,
            'name'       => $product->name,
            'specParams' => $product->params_on_spec,
            'params'     => $params,
        ]);
    }

}
