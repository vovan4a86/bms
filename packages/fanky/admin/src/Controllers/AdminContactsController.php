<?php namespace Fanky\Admin\Controllers;

use Fanky\Admin\Models\City;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\ContactTag;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Settings;
use Illuminate\Support\Str;
use Request;
use Validator;
use Text;
use Thumb;
use Image;
use Fanky\Admin\Models\Contact;

class AdminContactsController extends AdminController {

	public function getIndex() {
		$contacts = Contact::orderBy('order', 'asc')->paginate(100);

		return view('admin::contacts.main', compact('contacts'));
	}

	public function getEdit($id = null) {
		if (!$id || !($contact = Contact::find($id))) {
			$contact = new Contact;
		}
        $cities = City::orderBy('name')->get();

		return view('admin::contacts.edit', compact('contact', 'cities'));
	}

	public function postSave() {
		$id = Request::input('id');
		$data = Request::except(['id']);

		// валидация данных
		$validator = Validator::make(
			$data,[
                'title' => 'required',
			]);
		if ($validator->fails()) {
			return ['errors' => $validator->messages()];
		}

		// сохраняем страницу
		$contact = Contact::find($id);
		$redirect = false;
		if (!$contact) {
            $data['order'] = Contact::max('order') + 1;
			$contact = Contact::create($data);
			$redirect = true;
		} else {
			$contact->update($data);
		}

		if($redirect){
			return ['redirect' => route('admin.contacts.edit', [$contact->id])];
		} else {
			return ['msg' => 'Изменения сохранены.'];
		}

	}

	public function postDelete($id) {
		$contact = Contact::find($id);
		$contact->delete();

		return ['success' => true];
	}

    public function postUpdateOrder($id): array {
        $order = Request::get('order');
        Contact::whereId($id)->update(['order' => $order]);

        return ['success' => true];
    }


}
