<?php namespace Fanky\Admin\Controllers;

use Fanky\Admin\Models\Hardware as Model;
use Fanky\Admin\Models\HardwareCategory as CategoryModel;
use Fanky\Admin\Text;
use Request;
use Validator;

class AdminHardwareController extends AdminCatalogsAbstract {
	public function getIndex() {
		$items = $this->getItems(new Model());
		$categories = $this->getCategories(new CategoryModel());

		return view('admin::hardwares.products', [
			'items'      => $items,
			'categories' => $categories
		]);
	}

	public function getEdit($id = null) {
		$item = $this->getModel(new Model(), $id);
		$categories = $this->getCategories(new CategoryModel());
		if ($item == null) abort(404);

		if (!$item->id) {
			if (Request::has('select_category')) {
				$item->category_id = Request::get('select_category');
			}
			$item->published = 1;
		}

		return view('admin::hardwares.edit', [
			'item'       => $item,
			'categories' => $categories
		]);
	}

	public function postSave() {
		$id = Request::get('id', null);
		$item = $this->getModel(new Model(), $id);
		$data = Request::except('id', 'image');
		$image = Request::file('image');

		if (!array_get($data, 'alias')) $data['alias'] = Text::translit($data['name']);
		if (!array_get($data, 'title')) $data['title'] = $data['name'];
		if (!array_get($data, 'published')) $data['published'] = 0;

		$rules = [
			'name' => 'required',
		];
		$messages = [
			'name.required' => 'Не заполнено поле название',
		];

		$valid = Validator::make($data, $rules, $messages);
		if ($valid->fails()) {
			return ['errors' => $valid->messages()];
		}

		// Загружаем изображение
		if ($image) {
			$file_name = Model::uploadImage($image);
			$data['image'] = $file_name;
		}

		if ($item->image && isset($data['image'])) {
			$item->deleteImage();
		}

		$item->fill($data)->save();
		if (!$id || isset($data['image'])) {
			return ['redirect' => route('admin.hardware.edit', [$item->id])];
		} else {
			return ['msg' => 'Изменения сохранены.'];
		}
	}

	public function postDelete($id) {
		return $this->deleteModel(new Model(), $id);
	}

	public function getCategoryEdit($id = null) {
		$item = $this->getModel(new CategoryModel(), $id);
		$categories = $this->getCategories(new CategoryModel());
		if ($item == null) abort(404);

		if (!$item->id) {
			if (Request::has('select_category')) {
				$item->parent_id = Request::get('select_category');
			}
			$item->published = 1;
		}

		return view('admin::hardwares.category_edit', [
			'item'       => $item,
			'categories' => $categories
		]);
	}

	public function postCategorySave() {
		$id = Request::get('id', null);
		$item = $this->getModel(new CategoryModel(), $id);
		$data = Request::except('id', 'image');
		$image = Request::file('image');

		if (!array_get($data, 'alias')) $data['alias'] = Text::translit($data['name']);
		if (!array_get($data, 'title')) $data['title'] = $data['name'];
		if (!array_get($data, 'published')) $data['published'] = 0;

		$rules = [
			'name' => 'required',
		];
		$messages = [
			'name.required' => 'Не заполнено поле название',
		];

		$valid = Validator::make($data, $rules, $messages);
		if ($valid->fails()) {
			return ['errors' => $valid->messages()];
		}

		// Загружаем изображение
		if ($image) {
			$file_name = CategoryModel::uploadImage($image);
			$data['image'] = $file_name;
		}

		if ($item->image && isset($data['image'])) {
			$item->deleteImage();
		}

		$item->fill($data)->save();
		if (!$id || isset($data['image'])) {
			return ['redirect' => route('admin.hardware.category-edit', [$item->id])];
		} else {
			return ['msg' => 'Изменения сохранены.'];
		}
	}

	public function postCategoryDelete($id = null) {
		return $this->deleteModel(new CategoryModel(), $id);
	}

	public function getGetCatalogs($id = 0) {
		$catalogs = CategoryModel::whereParentId($id)->orderBy('order')->get();
		$result = [];
		foreach ($catalogs as $catalog) {
			$has_children = ($catalog->children()->count()) ? true : false;
			$result[] = [
				'id'       => $catalog->id,
				'text'     => $catalog->name,
				'children' => $has_children,
				'icon'     => ($catalog->published) ? 'fa fa-eye text-green' : 'fa fa-eye-slash text-muted',
			];
		}

		return $result;
	}

	public function postCategoryOrder() {
		// изменеие родителя
		$id = Request::input('id');
		$parent = Request::input('parent');
		CategoryModel::whereId($id)->update(['parent_id' => $parent]);
		$sorted = Request::input('sorted', []);
		$this->orderModel(new CategoryModel(), $sorted);

		return ['success' => true];
	}
}