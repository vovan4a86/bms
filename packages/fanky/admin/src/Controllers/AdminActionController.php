<?php namespace Fanky\Admin\Controllers;

use Exception;
use Fanky\Admin\Models\ActionProduct;
use Fanky\Admin\Models\ProductIcon;
use Request;
use Settings;
use Validator;
use Text;
use DB;
use Fanky\Admin\Models\Action;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\ProductImage;

class AdminActionController extends AdminController {

    public function getIndex() {
        $actions = Action::orderBy('order')->get();

        return view('admin::actions.main', [
            'actions' => $actions
        ]);
    }

    public function postProducts($action_id) {
        $action = Action::findOrFail($action_id);
        $products = $action->action_products()->orderBy('order')->get();

        $action_products = [];
        foreach ($products as $prod) {
            $action_products[] = Product::find($prod->product_id);
        }

        return view('admin::actions.products', [
            'action'  => $action,
            'all_products' => Product::public()->pluck('name', 'id')->all(),
            'action_products' => $action_products
        ]);
    }

    public function getProducts($action_id) {
        $actions = Action::orderBy('order')->get();

        return view('admin::actions.main', [
            'actions' => $actions,
            'content'  => $this->postProducts($action_id)
        ]);
    }

    public function postActionEdit($id = null) {
        /** @var Action $action */
        if(!$id || !($action = Action::findOrFail($id))) {
            $action = new Action([
                'parent_id'  => Request::get('parent'),
                'text_prev'  => Settings::get('action_text_prev_template'),
                'text_after' => Settings::get('action_text_after_template'),
                'published'  => 1,
                'date' => date('Y-m-d'),
                'badge' => 'Акция'
            ]);
        }
        $actions = Action::orderBy('order')
            ->where('id', '!=', $action->id)
            ->get();

        return view('admin::actions.action_edit', [
            'action'  => $action,
            'actions' => $actions
        ]);
    }

    public function getActionEdit($id = null) {
        $action = Action::orderBy('order')->get();

        return view('admin::actions.main', [
            'action' => $action,
            'content'  => $this->postActionEdit($id)
        ]);
    }

    public function postActionSave(): array {
        $id = Request::input('id');
        $data = Request::except(['id']);
        if(!array_get($data, 'alias')) $data['alias'] = Text::translit($data['name']);
        if(!array_get($data, 'title')) $data['title'] = $data['name'];
        if(!array_get($data, 'h1')) $data['h1'] = $data['name'];
        $image = Request::file('image');

        // валидация данных
        $validator = Validator::make($data, [
                'name' => 'required',
            ]);
        if($validator->fails()) {
            return ['errors' => $validator->messages()];
        }
        // Загружаем изображение
        if($image) {
            $file_name = Action::uploadIcon($image);
            $data['image'] = $file_name;
        }
        // сохраняем страницу
        $action = Action::find($id);
        $redirect = false;
        if(!$action) {
            $data['order'] = Action::where('parent_id', $data['parent_id'])->max('order') + 1;
            $action = Action::create($data);
            $redirect = true;

        } else {
            $action->update($data);
        }

        return $redirect
            ? ['redirect' => route('admin.actions.actionEdit', [$action->id])]
            : ['success' => true, 'msg' => 'Изменения сохранены'];
    }

    public function postActionReorder(): array {
        // изменеие родителя
        $id = Request::input('id');
        $parent = Request::input('parent');
        DB::table('actions')->where('id', $id)->update(array('parent_id' => $parent));
        // сортировка
        $sorted = Request::input('sorted', []);
        foreach($sorted as $order => $id) {
            DB::table('actions')->where('id', $id)->update(array('order' => $order));
        }

        return ['success' => true];
    }

    /**
     * @throws Exception
     */
    public function postActionDelete($id): array {
        $action = Action::findOrFail($id);
        $action->delete();

        return ['success' => true];
    }

    public function postProductEdit($id = null) {
        /** @var Product $product */
        if(!$id || !($product = Product::findOrFail($id))) {
            $product = new Product([
                'action_id'    => Request::get('action'),
                'published'     => 1,
            ]);
        }
        $actions = Action::getActionList();

        $data = [
            'product'  => $product,
        ];
        return view('admin::action.product_edit', $data);
    }

    public function getProductEdit($id = null) {
        $actions = Action::orderBy('order')->get();

        return view('admin::action.main', [
            'actions' => $actions,
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
            ? 'required|unique:products,alias,' . $id . ',id,action_id,' . $data['action_id']
            : 'required|unique:products,alias,null,id,action_id,' . $data['action_id'];
        // валидация данных
        $validator = Validator::make(
            $data, $rules
        );
        if($validator->fails()) {
            return ['errors' => $validator->messages()];
        }
        $redirect = false;
        // сохраняем страницу
        $product = Product::find($id);
        if(!$product) {
            $data['order'] = Product::where('action_id', $data['action_id'])->max('order') + 1;
            $product = Product::create($data);
            $redirect = true;
        } else {
            $product->update($data);
        }
        $product->icons()->sync($icons);

        return $redirect
            ? ['redirect' => route('admin.action.productEdit', [$product->id])]
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
            $html .= view('admin::action.product_image', ['image' => $item, 'active' => '']);
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

    public function getGetActions($id = 0): array {
        $actions = Action::whereParentId($id)->orderBy('order')->get();
        $result = [];
        foreach($actions as $action) {
            $has_children = (bool)$action->children()->count();
            $result[] = [
                'id'       => $action->id,
                'text'     => $action->name,
                'children' => $has_children,
                'icon'     => ($action->published) ? 'fa fa-eye text-green' : 'fa fa-eye-slash text-muted',
            ];
        }

        return $result;
    }

    public function postAddActionProduct($action_id) {
        $action = Action::findOrFail($action_id);
        $data = Request::all();
        $valid = Validator::make($data, [
            'product_id' => 'required',
        ]);

        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        } else {
            $data = array_map('trim', $data);
            $data['action_id'] = $action->id;
            $data['order'] = 0;
            $action_product = ActionProduct::create($data);
            $product = Product::find($data['product_id']);
            $row = view('admin::actions.product_row', ['product' => $product])->render();

            return ['row' => $row];
        }
    }

    public function postSaveActionProduct($related_id) {
        $product = ActionProduct::findOrFail($related_id);
        $data = Request::all();
        $data = array_map('trim', $data);
        $valid = Validator::make($data, [
            'related_id' => 'required',
        ]);

        if (!$valid->fails()) {
            $product->fill($data);
            $product->save();
        }

        return view('admin::actions.related_row', ['product' => $product])->render();
    }

    public function postDelActionProduct($prod_id) {
        $action_product = ActionProduct::findOrFail($prod_id);
        $action_product->delete();

        return ['success' => true];
    }

}
