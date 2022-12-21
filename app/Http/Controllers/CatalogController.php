<?php namespace App\Http\Controllers;

use Fanky\Admin\Cart;
use Fanky\Admin\Models\AddParam;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\CatalogParam;
use Fanky\Admin\Models\City;
use Fanky\Admin\Models\MaterialImage;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\ProductAddParam;
use Fanky\Admin\Models\ProductIcon;
use Fanky\Admin\Settings;
use Illuminate\Database\Eloquent\Collection;
//use Illuminate\Http\Request;
use SEOMeta;
use Session;
use View;
use Request;

class CatalogController extends Controller {

    public function region_index($city) {
        $this->city = City::current($city);

        return $this->index();
    }

    public function region_view($city_alias, $alias) {
        $this->city = City::current($city_alias);

        return $this->view($alias);
    }

    public function index() {
        $page = Page::getByPath(['catalog']);
        if(!$page) return abort(404);
        $bread = $page->getBread();
        $page->h1 = $page->getH1();
        $page = $this->add_region_seo($page);
        $page->setSeo();
        $categories = Catalog::getTopLevelOnList();

//        $cat = Catalog::find(1)->getRecurseProductsCount();
//        dd($cat);

        return view('catalog.index', [
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
        $category = Catalog::getByPath($path);
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
        /** @var Catalog $category */
        $category = Catalog::getByPath($path);
        if(!$category || !$category->published) abort(404, 'Страница не найдена');
        $bread = $category->getBread();
        $category = $this->add_region_seo($category);
        $category->setSeo();
        $children = $category->public_children;
//        $catalog_sub_id = $category->catalog_sub_show()->pluck('catalog_sub_show_id')->all();
//        $catalog_sub = Catalog::whereIn('id', $catalog_sub_id)->orderBy('order')->get();

        $categories = Catalog::getTopLevelOnList();

        $root = $category;
        while($root->parent_id !== 0) {
            $root = $root->findRootCategory($root->parent_id);
        }

        $per_page = Request::get('pages');
        $per_page = is_numeric($per_page) ? $per_page : \Settings::get('product_per_page');
        $data['per_page'] = $per_page;

//        $parentIds = Catalog::where('parent_id', '=', '0')->pluck('id')->all();

        $ids = [];
        if(count($children)) {
            $ids = $category->getRecurseChildrenIds();
        } else {
            $ids = $category->getRecurseChildrenIdsInner();
        }

        $filterSizes = Product::public()->whereIn('catalog_id', $ids)->distinct()->pluck( 'size')->all();
        $filterNames = Product::public()->whereIn('catalog_id', $ids)->distinct()->pluck( 'name')->all();


        $items = Product::public()->whereIn('catalog_id', $ids)
            ->orderBy('catalog_id')->paginate($per_page);
//        dd($items);
//        dd($items->pluck('name')->all());

//        $filters = $root->filters()->get();

//        $sort = [];
//        foreach ($filters as $filter) {
//            if($filter->cat_id === null) {
//                if($ids) {
//                    $sort[$filter->alias] = Product::public()->whereIn('catalog_id', $ids)
//                        ->orderBy($filter->alias, 'asc')
//                        ->groupBy($filter->alias)
//                        ->distinct()
//                        ->pluck($filter->alias)
//                        ->all();
//                } else {
//                    $sort[$filter->alias] = Product::public()->where('catalog_id', $category->id)
//                        ->orderBy($filter->alias, 'asc')
//                        ->groupBy($filter->alias)
//                        ->distinct()
//                        ->pluck($filter->alias)
//                        ->all();
//                }
//            } else {
//                $catalog_params = CatalogParam::where('catalog_id', '=', $filter->catalog_id)
//                    ->pluck('param_id')->all();
//                $prods = ProductAddParam::whereIn('add_param_id', $catalog_params)
//                    ->pluck('product_id')->all();
//                $sort[$filter->alias] = Product::whereIn('id', $prods)->get();
//            }
//        }

        $data = [
            'bread'    => $bread,
            'category' => $category,
            'categories' => $categories,
            'children' => $children,
            'h1'       => $category->getH1(),
            'updated' => $items[0]->updated_at ?? null,
            'items' => $items,
            'filterSizes' => $filterSizes,
            'filterNames' => $filterNames,
            'root' => $root ?? null,
            'headerIsWhite' => true,
        ];

//        $view = Session::get('catalog_view', 'list') == 'list' ?
//            'catalog.views.list' :
//            'catalog.views.grid';

//        $data['items'] = view($view, [
//            'items' => $items,
//            'category' => $category,
//            'sort' => $sort,
//            'root' => $root,
//            'filters' => $filters,
//            'per_page' => $per_page
//        ]);

//        if (Request::ajax()) {
//            $column1 = Request::only('column1');
//            $column2 = Request::only('column2');
//            $filter_name1 = Request::get('filter_name1');
//            $filter_name2 = Request::get('filter_name2');
//
//            $queries = [];
//            if (count($column1)) {
//                foreach ($column1 as $name => $values) {
//                    foreach ($values as $value) {
//                        $queries[$filter_name1][] = [$value];
//                    }
//                }
//            }
//
//            if (count($column2)) {
//                foreach ($column2 as $name => $values) {
//                    foreach ($values as $value) {
//                        $queries[$filter_name2][] = [$value];
//                    }
//                }
//            }
//
//
//            if(count($queries)) {
//                $prods_id = []; //все найденные id продуктов
//                foreach ($queries as $name => $values) {
//                    foreach ($values as $value) {
//                        $prods_id[] = Product::where('catalog_id', $category->id)->where($name, $value)->pluck('id');
//                    }
//                }
//
//                $products_ids = [];
//                foreach ($prods_id as $items) {
//                    foreach ($items as $item) {
//                        $products_ids[] = $item;
//                    }
//                }
////                \Debugbar::log($products_ids);
//                $items = Product::whereIn('id', $products_ids)
//                    ->orderBy('name')->paginate($per_page);
//            } else {
//                $items = Product::where('catalog_id', $category->id)
//                    ->orderBy('name')->paginate($per_page);
//            }
//
//            $view_items = [];
//            foreach ($items as $item) {
//                $view_items[] = view('catalog.list_row', [
//                    'item' => $item,
//                    'category' => $category,
//                    'sort' => $sort,
//                    'root' => $root,
//                    'filters' => $filters,
//                    'per_page' => $per_page
//                ])->render();
//            }
//
//            return response()->json([
//                'list' => $view_items,
//                'paginate' => view('catalog.list_pagination', [
//                    'items' => $items,
//                ])->render(),
////                'perpage' => view('catalog.views.per_page', [
////                    'category' => $category,
////                    'per_page' => $per_page
////                ])->render()
//            ]);
//        }

        return view('catalog.category', $data);
    }

    public function product(Product $product) {
        $bread = $product->getBread();
        $product = $this->add_region_seo($product);
        $product->setSeo();
//        $features = ProductIcon::orderBy('order', 'asc')->get();
        $categories = Catalog::getTopLevelOnList();

        $catalog = Catalog::whereId($product->catalog_id)->first();
        $root = $catalog;
        while($root->parent_id !== 0) {
            $root = $root->findRootCategory($root->parent_id);
        }
//        $params = $root->params()->get();

//        $add_params = ProductAddParam::where('product_id', '=', $product->id)
//            ->join('add_params', 'product_add_params.add_param_id', '=', 'add_params.id')
//            ->groupBy('name')
//            ->get();

//        $related = $product->related()->get(); //похожие товары добавленные из админки

        //похожие товары, добавленные вручную + из той же подкатегории
//        $related_from_cat = Product::whereCatalogId($catalog->id)
//            ->where('id', '<>', $product->id)->get();

        //если товаров в подкатегории нет => 10 случайных в категории
//        if(!count($related_from_cat)) {
//            $related_cat = $root->getAllPublicChildren()->pluck('id')->all();
//            $collection = Product::whereIn('catalog_id', $related_cat)
//                ->where('id', '<>', $product->id)->get();
//
//            if(!$collection->isEmpty()) {
//                if($collection->count() > 10) {
//                    $related_from_cat = $collection->random(10);
//                } else {
//                    $related_from_cat = $collection;
//                }
//            }
//        }

        //наличие в корзине
        $in_cart = false;
        if(Session::get('cart')) {
            $cart = array_keys(Session::get('cart'));
            if($cart) {
                $in_cart = in_array($product->id, $cart);
            }
        }

//        $related = $related->merge($related_from_cat);

//        $images = $product->images()->get();
        $image = $product->image()->first();

        if(!$product->text) {
            $text = $root->text;
        }

        $count_per_tonn = 0;
        $count_weight = 0;
        $summ = 0;
        if($product->price && $product->measure == 'т') {
            $cur_factor = 0.1; //если фактор не известен
            if($product->factor != 0) {
                $cur_factor = $product->factor / 1000;
            }
            do {
                $count_weight += $cur_factor;
                $count_per_tonn++;
            } while ($count_weight < 1);
            $summ = $count_weight * Product::fullPrice($product->price);

        } else if($product->price && $product->measure == 'м2') {
            $count_per_tonn = 1;
            if($product->factor_m2) {
                $count_weight = $product->factor_m2;
            } else {
                $dlina = 1;
                $shirina = 1;
                if($product->dlina) $dlina = preg_replace('/[А-Яа-я]/', '', $product->dlina);
                if($product->shirina) $shirina = preg_replace('/[А-Яа-я]/', '', $product->shirina);
                $count_weight = $dlina * $shirina;
            }
            $summ = $count_weight * Product::fullPrice($product->price);
        }

        return view('catalog.product', [
            'product'    => $product,
            'categories' => $categories,
            'in_cart'    => $in_cart,
            'text'       => $text ?? null,
            'bread'      => $bread,
            'headerIsWhite' => true,
            'name'       => $product->name,
            'specParams' => $product->params_on_spec,
            'params'     => $params ?? null,
            'add_params' => $add_params ?? null,
//            'features' => $features,
//            'related' => $related,
            'image' => $image ? $image->image : null,
            'cat_image' => $cat_image ?? null,
            'count_weight' => $count_weight,
            'count_per_tonn' => $count_per_tonn,
            'factor_m2_weight' => $product->factor_m2_weight ?? 1,
            'summ' => number_format($summ, 2 ,',', ' ' ),
        ]);
    }

}
