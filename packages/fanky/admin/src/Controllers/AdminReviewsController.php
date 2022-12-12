<?php namespace Fanky\Admin\Controllers;

use Request;
use Validator;
use DB;
use Fanky\Admin\YouTube;
use Fanky\Admin\Models\Review;

class AdminReviewsController extends AdminController {

	public function getIndex()
	{
		$reviews = Review::orderBy('order')->get();

		return view('admin::reviews.main', ['reviews' => $reviews]);
	}

	public function getEdit($id = null)
	{
		if (!$id || !($review = Review::findOrFail($id))) {
			$review = new Review;
		}

		return view('admin::reviews.edit', ['review' => $review]);
	}

	public function postSave()
	{
		$id = Request::input('id');
		$data = Request::only(['date', 'text', 'adress', 'type', 'on_main']);
		$video = Request::input('video');

		if (!$data['on_main']) $data['on_main'] = 0;
		if ($video) $data['video'] = YouTube::getId($video);

		// валидация данных
		$validator = Validator::make(
		    $data,
		    [
		    	'text' => 'required',
		    ]
		);
		if ($validator->fails()) {
			return ['errors' => $validator->messages()];
		}

		// сохраняем страницу
		$review = Review::find($id);
		if (!$review) {
			$data['order'] = Review::max('order') + 1;
			$review = Review::create($data);
			return ['redirect' => route('admin.reviews.edit', [$review->id])];
		} else {
			$old_video = $review->video;
			$review->update($data);
			if ($video && $old_video != $video) return ['redirect' => route('admin.reviews.edit', [$review->id])];
		}

		return ['msg' => 'Изменения сохранены.'];
	}

	public function postReorder()
	{
		$sorted = Request::input('sorted', []);
		foreach ($sorted as $order => $id) {
			DB::table('reviews')->where('id', $id)->update(array('order' => $order));
		}
		return ['success' => true];
	}

	public function postDelete($id)
	{
		$review = Review::find($id);
		$review->delete();

		return ['success' => true];
	}
}
