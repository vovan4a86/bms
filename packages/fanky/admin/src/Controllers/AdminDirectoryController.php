<?php namespace Fanky\Admin\Controllers;

use Fanky\Admin\Models\City;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\PartnerTag;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Settings;
use Illuminate\Support\Str;
use Request;
use Validator;
use Text;
use Thumb;
use Image;
use Fanky\Admin\Models\Partner;

class AdminDirectoryController extends AdminController {

	public function getIndex() {
		$partners = Partner::orderBy('order', 'asc')->paginate(100);

		return view('admin::partners.main', compact('partners'));
	}

	public function getEdit($id = null) {
		if (!$id || !($article = Partner::find($id))) {
			$article = new Partner;
			$article->date = date('Y-m-d');
			$article->published = 1;
		}
        $cities = City::orderBy('name')->get();

		return view('admin::partners.edit', compact('article', 'cities'));
	}

	public function postSave() {
		$id = Request::input('id');
		$data = Request::only(['name', 'site','order']);
        $image = Request::file('image');

		if (!array_get($data, 'alias')) $data['alias'] = Text::translit($data['name']);
		if (!array_get($data, 'title')) $data['title'] = $data['name'];

		// валидация данных
		$validator = Validator::make(
			$data,[
				'name' => 'required',
			]);
		if ($validator->fails()) {
			return ['errors' => $validator->messages()];
		}

        // Загружаем изображение
        if ($image) {
            $file_name = Partner::uploadImage($image);
            $data['image'] = $file_name;
        }

		// сохраняем страницу
		$article = Partner::find($id);
		$redirect = false;
		if (!$article) {
            $data['order'] = Partner::max('order') + 1;
			$article = Partner::create($data);
			$redirect = true;
		} else {
            if ($article->image && isset($data['image'])) {
                $article->deleteImage();
            }
			$article->update($data);
		}

		if($redirect){
			return ['redirect' => route('admin.partners.edit', [$article->id])];
		} else {
			return ['msg' => 'Изменения сохранены.'];
		}

	}

	public function postDelete($id) {
		$article = Partner::find($id);
		$article->delete();

		return ['success' => true];
	}

	public function postDeleteImage($id) {
		$partners = Partner::find($id);
		if(!$partners) return ['error' => 'partners_not_found'];

		$partners->deleteImage();
		$partners->update(['image' => null]);

		return ['success' => true];
	}

    public function postUpdateOrder($id): array {
        $order = Request::get('order');
        Partner::whereId($id)->update(['order' => $order]);

        return ['success' => true];
    }

    public function postUpdateImageWidth($id): array {
        $width = Request::get('image_width');
        Partner::whereId($id)->update(['image_width' => $width]);

        return ['success' => true];
    }

    public function postUpdateImageHeight($id): array {
        $height = Request::get('image_height');
        Partner::whereId($id)->update(['image_height' => $height]);

        return ['success' => true];
    }

}
