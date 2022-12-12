<?php namespace Fanky\Admin\Controllers;

use Fanky\Admin\Models\Publication;
use Fanky\Admin\Models\PublicationTag;
use Fanky\Admin\Settings;
use Illuminate\Support\Str;
use Request;
use Validator;
use Text;
use Thumb;
use Image;

class AdminPublicationsController extends AdminController {

	public function getIndex() {
		$publications = Publication::orderBy('date', 'desc')->paginate(100);

		return view('admin::publications.main', ['publications' => $publications]);
	}

	public function getEdit($id = null) {
		if (!$id || !($article = Publication::find($id))) {
			$article = new Publication();
			$article->date = date('Y-m-d');
			$article->published = 1;
		}

		return view('admin::publications.edit', ['article' => $article]);
	}

	public function postSave() {
		$id = Request::input('id');
		$data = Request::only(['date', 'name', 'announce', 'text', 'published', 'alias', 'title', 'keywords', 'description']);
		$tags = Request::get('tags', []);
		$image = Request::file('image');

		if (!array_get($data, 'alias')) $data['alias'] = Text::translit($data['name']);
		if (!array_get($data, 'title')) $data['title'] = $data['name'];
		if (!array_get($data, 'published')) $data['published'] = 0;

		// валидация данных
		$validator = Validator::make($data, [
				'name' => 'required',
				'date' => 'required',
			]);
		if ($validator->fails()) {
			return ['errors' => $validator->messages()];
		}

		// Загружаем изображение
		if ($image) {
			$file_name = Publication::uploadImage($image);
			$data['image'] = $file_name;
		}

		// сохраняем страницу
		$article = Publication::find($id);
		$redirect = false;
		if (!$article) {
			$article = Publication::create($data);
			$redirect = true;
		} else {
			if ($article->image && isset($data['image'])) {
				$article->deleteImage();
			}
			$article->update($data);
		}
//		$article->tags()->sync($tags);

		if($redirect){
			return ['redirect' => route('admin.publications.edit', [$article->id])];
		} else {
			return ['msg' => 'Изменения сохранены.'];
		}

	}

	public function postDelete($id) {
		$article = Publication::find($id);
		$article->delete();

		return ['success' => true];
	}

	public function getGetTags() {
		$q = Request::get('tag_name');
		$result = PublicationTag::where('tag', 'LIKE', '%'. $q . '%')
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
		$item = PublicationTag::firstOrCreate(['tag' => $tag]);
		$row = view('admin::publications.tag_row', ['tag' => $item])->render();

		return ['row' => $row];
	}
}
