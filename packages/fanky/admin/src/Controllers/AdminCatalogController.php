<?php namespace Fanky\Admin\Controllers;

use Exception;
use Fanky\Admin\Models\AddParam;
use Fanky\Admin\Models\CatalogParam;
use Fanky\Admin\Models\CatalogFilter;
use Fanky\Admin\Models\CatalogSubShow;
use Fanky\Admin\Models\Param;
use Fanky\Admin\Models\ProductAddParam;
use Fanky\Admin\Models\ProductIcon;
use Fanky\Admin\Models\ProductParam;
use Fanky\Admin\Models\ProductRelated;
use http\Params;
use Request;
use Settings;
use Validator;
use Text;
use DB;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\ProductImage;

class AdminCatalogController extends AdminController {

    public function getIndex() {
        $catalogs = Catalog::orderBy('order')->get();

        return view('admin::catalog.main', [
            'catalogs' => $catalogs
        ]);
    }

    public function postProducts($catalog_id) {
        $catalog = Catalog::findOrFail($catalog_id);
        $products = $catalog->products()->orderBy('order')->get();

        return view('admin::catalog.products', [
            'catalog'  => $catalog,
            'products' => $products
        ]);
    }

    public function getProducts($catalog_id) {
        $catalogs = Catalog::orderBy('order')->get();

        return view('admin::catalog.main', [
            'catalogs' => $catalogs,
            'content'  => $this->postProducts($catalog_id)
        ]);
    }

    public function postCatalogEdit($id = null) {
        /** @var Catalog $catalog */
        if(!$id || !($catalog = Catalog::findOrFail($id))) {
            $catalog = new Catalog([
                'parent_id'  => Request::get('parent'),
                'text_prev'  => Settings::get('catalog_text_prev_template'),
                'text_after' => Settings::get('catalog_text_after_template'),
                'published'  => 1
            ]);
        }
        $catalogs = Catalog::orderBy('order')
            ->where('id', '!=', $catalog->id)
            ->get();

        $filters = CatalogParam::where('catalog_id', '=', $catalog->id)
            ->join('params', 'catalog_params.param_id', '=', 'params.id')
            ->get();

        $filters_used = $catalog->filters()->pluck('param_id')->all();

        $show_catalogs = $catalog->catalog_sub_show()->pluck('catalog_sub_show_id')->all();

        return view('admin::catalog.catalog_edit', [
            'filters_used' => $filters_used,
            'filters' => $filters,
            'catalog'  => $catalog,
            'catalogs' => $catalogs,
            'show_catalogs' => $show_catalogs,
        ]);
    }

    public function getCatalogEdit($id = null) {
        $catalogs = Catalog::orderBy('order')->get();

        return view('admin::catalog.main', [
            'catalogs' => $catalogs,
            'content'  => $this->postCatalogEdit($id)
        ]);
    }

    public function postCatalogSave(): array {
        $id = Request::input('id');
        $data = Request::except(['id']);
        if(!array_get($data, 'alias')) $data['alias'] = Text::translit($data['name']);
        if(!array_get($data, 'title')) $data['title'] = $data['name'];
        if(!array_get($data, 'h1')) $data['h1'] = $data['name'];
        $image = Request::file('image');
        $filters = Request::get('filters', []);
        $checked_subcatalogs = Request::get('show_cats', []);

        // валидация данных
        $validator = Validator::make(
            $data,
            [
                'name' => 'required',
            ]
        );
        if($validator->fails()) {
            return ['errors' => $validator->messages()];
        }
        // Загружаем изображение
        if($image) {
            $file_name = Catalog::uploadImage($image);
            $data['image'] = $file_name;
        }
        // сохраняем страницу
        $catalog = Catalog::find($id);
        $redirect = false;
        if(!$catalog) {
            $data['order'] = Catalog::where('parent_id', $data['parent_id'])->max('order') + 1;
            $catalog = Catalog::create($data);
            $redirect = true;

        } else {
            //проверяем какие подкаталоги отмечены для вывода
            $show_catalogs = $catalog->public_children()->pluck('id')->all();
            foreach ($show_catalogs as $id) {
                if(in_array($id, $checked_subcatalogs)) {
                    $item = CatalogSubShow::where('catalog_id', $catalog->id)->where('catalog_sub_show_id', $id)->first();
                    if(!$item) {
                        CatalogSubShow::create([
                            'catalog_id' => $catalog->id,
                            'catalog_sub_show_id' => $id
                        ]);
                    }
                } else {
                    //удаляем, если у подкаталога убрана галочка
                    $item = CatalogSubShow::where('catalog_id', $catalog->id)->where('catalog_sub_show_id', $id)->first();
                    if($item) $item->delete();
                }
            }

            $catalog->update($data);
        }

        if($redirect) {
            return ['redirect' => route('admin.catalog.catalogEdit', [$catalog->id])];
        } else {
            $catalog->catalog_filters()->sync($filters);
            return ['success' => true, 'msg' => 'Изменения сохранены'];
        }
    }

    public function postCatalogReorder(): array {
        // изменеие родителя
        $id = Request::input('id');
        $parent = Request::input('parent');
        DB::table('catalogs')->where('id', $id)->update(array('parent_id' => $parent));
        // сортировка
        $sorted = Request::input('sorted', []);
        foreach($sorted as $order => $id) {
            DB::table('catalogs')->where('id', $id)->update(array('order' => $order));
        }

        return ['success' => true];
    }

    /**
     * @throws Exception
     */
    public function postCatalogDelete($id): array {
        $catalog = Catalog::findOrFail($id);
        $catalog->delete();

        return ['success' => true];
    }

    public function postProductEdit($id = null) {
        /** @var Product $product */
        if(!$id || !($product = Product::findOrFail($id))) {
            $product = new Product([
                'catalog_id'    => Request::get('catalog'),
                'published'     => 1,
                'measure' => 'т',
            ]);
        }
        $catalogs = Catalog::getCatalogList();
        $product_list = Product::where('id', '<>', $product->id)->public()->pluck('name', 'id')->all();

        $category = Catalog::where('id', '=', $product->catalog_id)->first();
        if($category->parent_id !== 0) {
            $root = $category->findRootCategory($category->parent_id);
        } else {
            $root = $category;
        }
//        $add_params = $root->add_params()->get();
//        dd($add_params);

        $add_params = Param::where('cat_id', '=', $root->id)
            ->where('product_id', '=', $product->id)
            ->join('product_add_params', 'params.id', '=', 'product_add_params.add_param_id')
            ->get();

        $data = [
            'product'  => $product,
            'catalogs' => $catalogs,
            'product_list' => $product_list,
            'add_params' => $add_params,
        ];
        return view('admin::catalog.product_edit', $data);
    }

    public function getProductEdit($id = null) {
        $catalogs = Catalog::orderBy('order')->get();

        return view('admin::catalog.main', [
            'catalogs' => $catalogs,
            'content'  => $this->postProductEdit($id)
        ]);
    }

    public function postProductSave(): array {
        $id = Request::get('id');
        $data = Request::except(['id', 'icons']);
        $icons = Request::get('icons', []);

        if(!array_get($data, 'published')) $data['published'] = 0;
        if(!array_get($data, 'alias')) $data['alias'] = Text::translit($data['name']);
        if(!array_get($data, 'title')) $data['title'] = $data['name'];
        if(!array_get($data, 'h1')) $data['h1'] = $data['name'];

        $rules = [
            'name' => 'required'
        ];

        $rules['alias'] = $id
            ? 'required|unique:products,alias,' . $id . ',id,catalog_id,' . $data['catalog_id']
            : 'required|unique:products,alias,null,id,catalog_id,' . $data['catalog_id'];
        // валидация данных
        $validator = Validator::make(
            $data, $rules
        );
        if($validator->fails()) {
            return ['errors' => $validator->messages()];
        }
        $redirect = false;

        $catalog = Catalog::find($data['catalog_id']);
        if($catalog->parent_id !== 0) {
            $root = $catalog->findRootCategory($catalog->parent_id);
        } else {
            $root = $catalog;
        }
        $add_params = $root->add_params()->get();

        // сохраняем страницу
        $product = Product::find($id);
        if(!$product) {
            $data['order'] = Product::where('catalog_id', $data['catalog_id'])->max('order') + 1;
            $product = Product::create($data);
            $redirect = true;
            $arr = [];
            foreach ($add_params as  $param) {
                $arr['product_id'] = $product->id;
                $arr['add_param_id'] = $param->param_id;
                $arr['value'] = Request::get($param->alias);
                ProductAddParam::create($arr);
            }

        } else {
            $product->update($data);
            $arr = [];
            foreach ($add_params as  $param) {
                $par = ProductAddParam::where('product_id', '=', $product->id)
                    ->where('add_param_id', '=', $param->param_id)->first();
                $arr['product_id'] = $product->id;
                $arr['add_param_id'] = $param->param_id;
                $arr['value'] = Request::get($param->alias);

                if($par) {
                    $par->update($arr);
                }
            }
        }

        return $redirect
            ? ['redirect' => route('admin.catalog.productEdit', [$product->id])]
            : ['success' => true, 'msg' => 'Изменения сохранены'];
    }

    public function postProductReorder(): array {
        $sorted = Request::input('sorted', []);
        foreach($sorted as $order => $id) {
            DB::table('products')->where('id', $id)->update(array('order' => $order));
        }

        return ['success' => true];
    }

    public function postUpdateOrder($id): array {
        $order = Request::get('order');
        Product::whereId($id)->update(['order' => $order]);

        return ['success' => true];
    }

    public function postProductDelete($id): array {
        $product = Product::findOrFail($id);
        foreach($product->images as $item) {
            $item->deleteImage();
            $item->delete();
        }
        $product->delete();

        return ['success' => true];
    }

    public function postProductImageUpload($product_id): array {
        $product = Product::findOrFail($product_id);
        $images = Request::file('images');
        $items = [];
        if($images) foreach($images as $image) {
            $file_name = ProductImage::uploadImage($image);
            $order = ProductImage::where('product_id', $product_id)->max('order') + 1;
            $item = ProductImage::create(['product_id' => $product_id, 'image' => $file_name, 'order' => $order]);
            $items[] = $item;
        }

        $html = '';
        foreach($items as $item) {
            $html .= view('admin::catalog.product_image', ['image' => $item, 'active' => '']);
        }

        return ['html' => $html];
    }

    public function postProductImageOrder(): array {
        $sorted = Request::get('sorted', []);
        foreach($sorted as $order => $id) {
            ProductImage::whereId($id)->update(['order' => $order]);
        }

        return ['success' => true];
    }

    /**
     * @throws Exception
     */
    public function postProductImageDelete($id): array {
        /** @var ProductImage $item */
        $item = ProductImage::findOrFail($id);
        $item->deleteImage();
        $item->delete();

        return ['success' => true];
    }

    public function getGetCatalogs($id = 0): array {
        $catalogs = Catalog::whereParentId($id)->orderBy('order')->get();
        $result = [];
        foreach($catalogs as $catalog) {
            $has_children = (bool)$catalog->children()->count();
            $result[] = [
                'id'       => $catalog->id,
                'text'     => $catalog->name,
                'children' => $has_children,
                'icon'     => ($catalog->published) ? 'fa fa-eye text-green' : 'fa fa-eye-slash text-muted',
            ];
        }

        return $result;
    }

    public function postAddRelated($product_id) {
        $product = Product::findOrFail($product_id);
        $data = Request::all();
        $valid = Validator::make($data, [
            'related_id' => 'required',
        ]);

        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        } else {
            $data = array_map('trim', $data);
            $data['product_id'] = $product->id;
            $data['order'] = 0;
            $related = ProductRelated::create($data);
            $row = view('admin::catalog.related_row', ['related' => $related])->render();

            return ['row' => $row];
        }
    }

    public function postDelRelated($related_id) {
        $related = ProductRelated::findOrFail($related_id);
        $related->delete();

        return ['success' => true];
    }

    public function postAddParam($catalog_id) {
        $data = Request::all();
        $valid = Validator::make($data, [
            'name'  => 'required',
        ]);

        if($valid->fails()) {
            return ['errors' => $valid->messages()];
        } else {
            $data = array_map('trim', $data);
            $data['cat_id'] = $catalog_id;
            $data['alias'] = Text::translit($data['name']);
            $data['title'] = $data['name'];
            $param = Param::create($data);

            CatalogParam::create([
                'catalog_id' => $catalog_id,
                'param_id' => $param->id,
                'order' => 1
            ]);
            CatalogFilter::create([
                'catalog_id' => $catalog_id,
                'param_id' => $param->id,
            ]);
            $row = view('admin::catalog.param_row', ['param' => $param])->render();

            return ['row' => $row];
        }
    }

    public function postDelParam($param_id) {
        $param = AddParam::findOrFail($param_id);
        $param->delete();

        return ['success' => true];
    }

    public function postEditParam($param_id) {
        $param = AddParam::findOrFail($param_id);

        return view('admin::catalog.param_edit', ['param' => $param])->render();
    }

    public function postSaveParam($param_id) {
        $param = AddParam::findOrFail($param_id);
        $data = Request::all();
        $data = array_map('trim', $data);
        $valid = Validator::make($data, [
            'name'  => 'required',
        ]);

        if(!$valid->fails()) {
            $param->fill($data);
            $param->save();
        }

        return view('admin::catalog.param_row', ['param' => $param])->render();
    }

    public function postUpdateFilterTitle($filter_id) {
        $param = Param::findOrFail($filter_id);
        $data = Request::all();
        $data = array_map('trim', $data);
        $valid = Validator::make($data, [
            'title'  => 'required',
        ]);

        if(!$valid->fails()) {
            $param->fill($data);
            $param->save();
            return ['success' => true];
        } else {
            return ['success' => false];
        }

    }
}
