<?php namespace App\Http\Controllers;

use Fanky\Admin\Cart;
use Fanky\Admin\Models\AddParam;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\CatalogParam;
use Fanky\Admin\Models\City;
use Fanky\Admin\Models\Filter;
use Fanky\Admin\Models\MaterialImage;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\ProductFilters;
use Fanky\Admin\Models\ProductIcon;
use Fanky\Admin\Settings;
use Illuminate\Database\Eloquent\Collection;

//use Illuminate\Http\Request;
use SEOMeta;
use Session;
use View;
use Request;

class CatalogController extends Controller
{

    public function region_index($city)
    {
        $this->city = City::current($city);

        return $this->index();
    }

    public function region_view($city_alias, $alias)
    {
        $this->city = City::current($city_alias);

        return $this->view($alias);
    }

    public function index()
    {
        $page = Page::getByPath(['catalog']);
        if (!$page) return abort(404);
        $bread = $page->getBread();
        $page->h1 = $page->getH1();
        $page = $this->add_region_seo($page);
        $page->setSeo();
        $categories = Catalog::getTopLevelOnList();
        $updated = Catalog::getUpdatedAt()->updated_at;

        return view('catalog.index', [
            'h1' => $page->h1,
            'text' => $page->text,
            'title' => $page->title,
            'bread' => $bread,
            'categories' => $categories,
            'headerIsWhite' => true,
            'updated' => date_format($updated, 'd.m.Y'),
        ]);
    }

    public function view($alias)
    {
        $path = explode('/', $alias);
        /* проверка на продукт в категории */
        $product = null;
        $end = array_pop($path);
        $category = Catalog::getByPath($path);
        if ($category && $category->published) {
            $product = Product::whereAlias($end)
                ->public()
                ->whereCatalogId($category->id)->first();
        }
        if ($product) {
            return $this->product($product);
        } else {
            array_push($path, $end);

            return $this->category($path + [$end]);
        }
    }

    public function category($path)
    {
        /** @var Catalog $category */
        $category = Catalog::getByPath($path);
        if (!$category || !$category->published) abort(404, 'Страница не найдена');
        $bread = $category->getBread();
        $category = $this->add_region_seo($category);
        $category->setSeo();

        $children = $category->public_children;
        $categories = Catalog::getTopLevelOnList();

        $root = $category;
        while ($root->parent_id !== 0) {
            $root = $root->findRootCategory($root->parent_id);
        }

        $per_page = Request::get('pages');
        $per_page = is_numeric($per_page) ? $per_page : \Settings::get('product_per_page');
        $data['per_page'] = $per_page;

        if (count($children)) {
            $ids = $category->getRecurseChildrenIds();
        } else {
            $ids = $category->getRecurseChildrenIdsInner();
        }

        $filterNames = Product::public()->whereIn('catalog_id', $ids)->distinct()->pluck('name')->all();
        $filterSizes = Product::public()->whereIn('catalog_id', $ids)->distinct()->orderBy('size')->pluck('size')->all();

        if ($category->filters) {
            [$filter1, $filter2] = explode('/', $category->filters);
            if (isset($filter1) && isset($filter2)) {
                foreach ([$filter1, $filter2] as $i => $filter) {
                    $filters[$i]['alias'] = $filter;
                    $filters[$i]['name'] = Filter::whereAlias($filter)->first()->name ?? 'noname';
                }
            }
        } else {
            $filters = [
                ['alias' => 'steel', 'name' => 'Марка'],
                ['alias' => 'length', 'name' => 'Длина']
            ];
        }

        $items = Product::public()->whereIn('catalog_id', $ids)
            ->orderBy('catalog_id')->paginate($per_page);

        $data = [
            'bread' => $bread,
            'category' => $category,
            'categories' => $categories,
            'children' => $children,
            'h1' => $category->getH1(),
            'updatedDate' => date_format($category->updated_at, 'd.m.Y'),
            'items' => $items,
            'filterSizes' => $filterSizes,
            'filterNames' => $filterNames,
            'filters' => $filters ?? null,
            'root' => $root ?? null,
            'headerIsWhite' => true,
        ];

        if (Request::ajax()) {
            $filter_name = Request::only('name'); //only = array
            $filter_size = Request::only('size');

            $searchCatalog = Request::get('search-catalog'); //get = string

            $queries = [];
            if (count($filter_name)) {
                foreach ($filter_name as $name => $values) {
                    foreach ($values as $value) {
                        $queries['name'][] = [$value];
                    }
                }
            }

            if (count($filter_size)) {
                foreach ($filter_size as $name => $values) {
                    foreach ($values as $value) {
                        $queries['size'][] = $value;
                    }
                }
            }

            if (count($queries)) {
                $prods_id = []; //все найденные id продуктов
                if ($category->parent_id !== 0) {
                    foreach ($queries as $name => $values) {
                        foreach ($values as $value) {
                            $prods_id[] = Product::where('catalog_id', $category->id)
                                ->where($name, $value)->pluck('id');
                        }
                    }
                } else {
                    foreach ($queries as $name => $values) {
                        foreach ($values as $value) {
                            $prods_id[] = Product::whereIn('catalog_id', $ids)
                                ->where($name, $value)->pluck('id');
                        }
                    }
                }


                $products_ids = [];//более удобный массив
                foreach ($prods_id as $items) {
                    foreach ($items as $item) {
                        $products_ids[] = $item;
                    }
                }

                if (isset($searchCatalog)) {
                    $items = Product::whereIn('id', $products_ids)
                        ->where('name', 'like', '%' . $searchCatalog . '%')
                        ->orderBy('name')->paginate($per_page);
                } else {
                    $items = Product::whereIn('id', $products_ids)
                        ->orderBy('name')->paginate($per_page);
                }

            } else {
                if (isset($searchCatalog)) {
                    $items = Product::where('catalog_id', $category->id)
                        ->where('name', 'like', '%' . $searchCatalog . '%')
                        ->orderBy('name')->paginate($per_page);
                } else {
                    if ($category->parent_id !== 0) {
                        $items = Product::where('catalog_id', $category->id)
                            ->orderBy('name')->paginate($per_page);
                    } else {
                        $items = Product::whereIn('catalog_id', $ids)
                            ->orderBy('name')->paginate($per_page);
                    }
                }
            }

            $view_items = [];
            foreach ($items as $item) {
                $view_items[] = view('catalog.product_item', [
                    'item' => $item,
                    'category' => $category,
                    'filters' => $filters ?? null,
                    'root' => $root,
                    'per_page' => $per_page
                ])->render();
            }

            return response()->json([
                'list' => $view_items,
                'paginate' => view('paginations.with_pages', [
                    'paginator' => $items,
                ])->render(),
            ]);
        }

        return view('catalog.category', $data);
    }

    public function product(Product $product)
    {
        $bread = $product->getBread();
        $product = $this->add_region_seo($product);
        $product->generateTitle();
        $product->generateDescription();
        $product->generateText();
        $product->setSeo();
//        $features = ProductIcon::orderBy('order', 'asc')->get();
        $categories = Catalog::getTopLevelOnList();

        $catalog = Catalog::whereId($product->catalog_id)->first();
        $root = $catalog;
        while ($root->parent_id !== 0) {
            $root = $root->findRootCategory($root->parent_id);
        }

        $similar = Product::whereName($product->name)->where('alias', '<>', $product->alias)->get();

//        $params = $root->params()->get();

//        $add_params = ProductAddParam::where('product_id', '=', $product->id)
//            ->join('add_params', 'product_add_params.add_param_id', '=', 'add_params.id')
//            ->groupBy('name')
//            ->get();

        $related = $product->related()->get(); //похожие товары добавленные из админки
//        dd($related);

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
        if (Session::get('cart')) {
            $cart = array_keys(Session::get('cart'));
            if ($cart) {
                $in_cart = in_array($product->id, $cart);
            }
        }

//        $related = $related->merge($related_from_cat);

        $prodImage = $product->image()->first();
        if ($prodImage) {
            $image = $prodImage->image;
        } else {
            $image = Catalog::whereId($product->catalog_id)->first()->section_image;
        }

        if (!$product->text) {
            $text = $root->text;
        }

        $count_per_tonn = 0;
        $count_weight = 0;
        $summ = 0;
        if ($product->price && $product->measure == 'т') {
            $cur_factor = 0.1; //если фактор не известен
            if ($product->factor != 0) {
                $cur_factor = $product->factor / 1000;
            }
            do {
                $count_weight += $cur_factor;
                $count_per_tonn++;
            } while ($count_weight < 1);
            $summ = $count_weight * Product::fullPrice($product->price);

        } else if ($product->price && $product->measure == 'м2') {
            $count_per_tonn = 1;
            if ($product->factor_m2) {
                $count_weight = $product->factor_m2;
            } else {
                $dlina = 1;
                $shirina = 1;
                if ($product->dlina) $dlina = preg_replace('/[А-Яа-я]/', '', $product->dlina);
                if ($product->shirina) $shirina = preg_replace('/[А-Яа-я]/', '', $product->shirina);
                $count_weight = $dlina * $shirina;
            }
            $summ = $count_weight * Product::fullPrice($product->price);
        }

        return view('catalog.product', [
            'product' => $product,
            'categories' => $categories,
            'in_cart' => $in_cart,
            'text' => $text ?? null,
            'bread' => $bread,
            'headerIsWhite' => true,
            'name' => $product->name,
            'specParams' => $product->params_on_spec,
            'params' => $params ?? null,
            'add_params' => $add_params ?? null,
//            'features' => $features,
            'similar' => $similar,
            'related' => $related,
            'image' => $image,
            'cat_image' => $cat_image ?? null,
            'count_weight' => $count_weight,
            'count_per_tonn' => $count_per_tonn,
            'factor_m2_weight' => $product->factor_m2_weight ?? 1,
            'summ' => number_format($summ, 2, ',', ' '),
        ]);
    }

}
