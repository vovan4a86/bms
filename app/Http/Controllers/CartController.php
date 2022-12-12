<?php namespace App\Http\Controllers;

use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request as Request;
use Illuminate\Mail\Message;
use Mailer;
use Settings;
use Cart;
use Fanky\Admin\Models\Order as Order;
use Response;

class CartController extends Controller {

	public function getIndex() {
//        Cart::purge();
		$items = Cart::all();
//        dd($items);
//		if (!count($items)) {
//			return Response::redirectTo(route('catalog.index', [], false), 307);
//		}
		$sum = 0;
        $total_weight = 0;
        foreach ($items as $key => $item) {
			$sum += Product::fullPrice($item['price']) * $item['weight'];
            $total_weight += $item['weight'];
			$product = Product::find($item['id']);
			if ($product) {
                $catalog = Catalog::find($product->catalog_id);
                $root = $catalog;
                while($root->parent_id !== 0) {
                    $root = $root->findRootCategory($root->parent_id);
                }
//                $items[$key]['image'] = $product->image ? Product::UPLOAD_URL . $product->image->image : Catalog::UPLOAD_URL . $root->image;
                $items[$key]['image'] = $product->showAnyImage();
			} else {
				$items[$key]['image'] = '\static\images\common\no_image.png';
			}
		}

        $page = Page::getByPath(['cart']);
        $bread = $page->getBread();


        return view('cart.index', [
			'items' => $items,
            'sum' => $sum,
            'total_weight' => $total_weight,
            'bread' => $bread
		]);
	}

	public function postIndex(Request $request) {
		$result = ['error' => false, 'msg' => ''];
		$messages = array(
			'email.required'           => 'Не указан ваш e-mail адрес!',
			'email.email'              => 'Не корректный e-mail адрес!',
			'name.required'            => 'Не заполнено поле Имя',
			'phone.required'           => 'Не заполнено поле Телефон',
			'delivery_method.required' => 'Не выбран способ доставки',
			'payment_method.required'  => 'Не выбран способ оплаты',
		);
		$this->validate($request, [
//			'name'            => 'required',
//			'email'           => 'required|email',
//			'phone'           => 'required',
//			'delivery_method' => 'required',
//			'payment_method'  => 'required',
		], $messages);
		$data = $request->only(['delivery_method', 'payment_method', 'name', 'phone', 'email']);
		/** @var Order $order */
		$order = Order::create($data);
		$items = Cart::all();
		$summ = 0;
		$all_count = 0;
		foreach ($items as $item) {
			$order->products()->attach($item['id'], [
				'count' => $item['count'],
				'price' => $item['price']
			]);
			$summ += $item['count'] * Product::fullPrice($item['price']);
			$all_count += $item['count'];
		}
		$order->update(['summ' => $summ]);

//		Mailer::sendNotification('mail.order',[
//			'order' => $order,
//			'items'	=> $items,
//			'all_count'	=> $all_count,
//			'all_summ'	=> $summ
//		], function($message){
//			$to = Settings::get('order_email');
//
//			/** @var Message $message */
//			$message->from('info@allant.ru', 'allant.ru - уведомления')
//				->to($to)
//				->subject('allant.ru - Новый заказ');
//		});

		Cart::purge();

		return json_encode($result);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function formatValidationErrors(Validator $validator) {
		$msg = $validator->errors()->all('<p>:message</p>');

		return ['error' => true, 'msg' => implode('', $msg)];
	}

    public function showSuccess($id) {
//        $id = $request->get('id');

        return view('cart.success', [
            'id' => $id,
        ]);
    }
}
