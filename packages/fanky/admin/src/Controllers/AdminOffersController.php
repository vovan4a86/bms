<?php namespace Fanky\Admin\Controllers;

use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Offer;
use Fanky\Admin\Settings;
use Illuminate\Support\Str;
use Request;
use Validator;
use Text;
use Thumb;
use Image;
use Fanky\Admin\Models\News;

class AdminOffersController extends AdminController {

	public function getIndex() {
		$offers = Offer::orderBy('date', 'desc')->paginate(100);

		return view('admin::offers.main', ['offers' => $offers]);
	}

	public function getEdit($id = null) {
		if (!$id || !($offer = Offer::find($id))) {
            $offer = new Offer;
            $offer->date = date('Y-m-d');
            $offer->published = 1;
		}

		return view('admin::offers.edit', ['offer' => $offer]);
	}

	public function postSave() {
		$id = Request::input('id');
		$data = Request::only(['date', 'name', 'announce', 'text', 'published', 'alias', 'title', 'keywords', 'description', 'on_main_show']);
		$image = Request::file('image');

		if (!array_get($data, 'alias')) $data['alias'] = Text::translit($data['name']);
		if (!array_get($data, 'title')) $data['title'] = $data['name'];
		if (!array_get($data, 'published')) $data['published'] = 0;

		// валидация данных
		$validator = Validator::make(
			$data,[
				'name' => 'required',
				'date' => 'required',
			]);
		if ($validator->fails()) {
			return ['errors' => $validator->messages()];
		}

		// Загружаем изображение
		if ($image) {
			$file_name = Offer::uploadImage($image);
			$data['image'] = $file_name;
		}

		// сохраняем страницу
		$offer = Offer::find($id);
		$redirect = false;
		if (!$offer) {
            $offer = Offer::create($data);
			$redirect = true;
		} else {
			if ($offer->image && isset($data['image'])) {
                $offer->deleteImage();
			}
            $offer->update($data);
		}

		if($redirect){
			return ['redirect' => route('admin.offers.edit', [$offer->id])];
		} else {
			return ['msg' => 'Изменения сохранены.'];
		}

	}

	public function postDelete($id) {
        $offer = Offer::find($id);
        $offer->delete();

		return ['success' => true];
	}

	public function postDeleteImage($id) {
        $offer = Offer::find($id);
		if(!$offer) return ['error' => 'offer_not_found'];

		$offer->deleteImage();
        $offer->update(['image' => null]);

		return ['success' => true];
	}
}
