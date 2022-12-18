<?php namespace Fanky\Admin\Controllers;

use Fanky\Admin\Models\City;
use Fanky\Admin\Models\VacancyTag;
use Fanky\Admin\Settings;
use Illuminate\Support\Str;
use Request;
use Validator;
use Text;
use Thumb;
use Image;
use Fanky\Admin\Models\Vacancy;

class AdminVacanciesController extends AdminController {

	public function getIndex() {
		$vacancies = Vacancy::orderBy('date', 'desc')->paginate(100);

		return view('admin::vacancies.main', compact('vacancies'));
	}

	public function getEdit($id = null) {
		if (!$id || !($article = Vacancy::find($id))) {
			$article = new Vacancy;
			$article->date = date('Y-m-d');
			$article->published = 1;
		}
        $cities = City::orderBy('name')->get();

		return view('admin::vacancies.edit', compact('article', 'cities'));
	}

	public function postSave() {
		$id = Request::input('id');
		$data = Request::only(['date', 'name', 'city_id', 'price', 'text', 'published', 'alias', 'title', 'keywords', 'description']);
        $cityName = City::find($data['city_id'])->first();

		if (!array_get($data, 'alias')) $data['alias'] = Text::translit($data['name'] . '-' . $cityName->name );
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

		// сохраняем страницу
		$article = Vacancy::find($id);
		$redirect = false;
		if (!$article) {
			$article = Vacancy::create($data);
			$redirect = true;
		} else {
			$article->update($data);
		}

		if($redirect){
			return ['redirect' => route('admin.vacancies.edit', [$article->id])];
		} else {
			return ['msg' => 'Изменения сохранены.'];
		}

	}

	public function postDelete($id) {
		$article = Vacancy::find($id);
		$article->delete();

		return ['success' => true];
	}

	public function getGetTags() {
		$q = Request::get('tag_name');
		$result = VacancyTag::where('tag', 'LIKE', '%'. $q . '%')
			->limit(10)
			->get()
			->transform(function($item){
				return ['id' => $item->id, 'name' => $item->tag];
			});

		return ['data' => $result];
	}

	public function postAddTag() {
		$tag = Request::get('tag');
		$tag = Str::ucfirst($tag);
		$item = VacancyTag::firstOrCreate(['tag' => $tag]);
		$row = view('admin::vacancies.tag_row', ['tag' => $item])->render();

		return ['row' => $row];
	}

	public function postDeleteImage($id) {
		$vacancies = Vacancy::find($id);
		if(!$vacancies) return ['error' => 'vacancies_not_found'];

		$vacancies->deleteImage();
		$vacancies->update(['image' => null]);

		return ['success' => true];
	}
}
