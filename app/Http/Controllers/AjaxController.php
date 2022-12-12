<?php namespace App\Http\Controllers;

use DB;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\City;
use Fanky\Admin\Models\Feedback;
use Fanky\Admin\Models\Order as Order;
use Fanky\Admin\Models\Page;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\ProductParam;
use Fanky\Admin\Models\Setting;
use Illuminate\Http\Request;
use Mail;
use Mailer;

//use Settings;
use Cart;
use Session;
use Settings;
use SiteHelper;
use Validator;

class AjaxController extends Controller
{
    private $fromMail = 'info@stalservis96.ru';
    private $fromName = 'Stal-Service';

    //РАБОТА С КОРЗИНОЙ

    public function postAddToCart(Request $request) {
        $id = $request->get('id');
        $count_per_tonn = $request->get('count_per_tonn', 0);
        $count_weight = $request->get('count_weight', 0);

        /** @var Product $product */
        $product = Product::find($id);
        if ($product) {
            $product_item['image'] = $product->showAnyImage();
            $product_item = $product->toArray();
            $product_item['dlina'] = $product->getLength();
            $product_item['count_per_tonn'] = $count_per_tonn;
            $product_item['count_weight'] = $count_weight;
            $product_item['url'] = $product->url;
            if(!$product_item['factor']) !$product_item['factor'] = 100;
//            $product_item['image'] = $image ? $image->thumb(2) : null;

            Cart::add($product_item);
        }
        $header_cart = view('blocks.header_cart')->render();
//        $popup = view('blocks.product_added', $product_item)->render();
        $buttons = view('cart.card_actions', ['product' => $product])->render();

        return [
            'header_cart' => $header_cart,
//            'popup' => $popup,
            'buttons' => $buttons
        ];
    }

    public function postEditCartProduct(Request $request)
    {
        $id = $request->get('id');
        $count = $request->get('count', 1);
        /** @var Product $product */
        $product = Product::find($id);
        if ($product) {
            $product_item['image'] = $product->showAnyImage();
            $product_item = $product->toArray();
            $product_item['count_per_tonn'] = $count;
            $product_item['url'] = $product->url;
//            $product_item['image'] = $image ? $image->thumb(2) : null;

            Cart::add($product_item);
        }

        $popup = view('blocks.cart_popup', $product_item)->render();

        return ['cart_popup' => $popup];
    }

    public function postUpdateToCart(Request $request) {
        $id = $request->get('id');
        $count = count(Cart::all());
        $count_per_tonn = $request->get('count', 1);
        $weight = $request->get('weight', 1);
        Cart::updateCount($id, $count_per_tonn, $weight);

        $product = Product::find($id);
        $product_item = $product->toArray();
        $product_item['url'] = $product->url;
        $product_item['count_weight'] = $weight;
        $product_item['count_per_tonn'] = $count_per_tonn;

        $catalog = Catalog::find($product->catalog_id);
        $root = $catalog;
        while($root->parent_id !== 0) {
            $root = $root->findRootCategory($root->parent_id);
        }
        $product_item['image'] - $product->showAnyImage();
//        $product_item['image'] = $product->image ?
//            Product::UPLOAD_URL . $product->image->image :
//            Catalog::UPLOAD_URL . $root->image;

        $item = view('cart.table_row', ['item' => $product_item])->render();
        $header_cart = view('blocks.header_cart')->render();
        $total = view('cart.blocks.total')->render();
        $summ = view('cart.blocks.summ')->render();
        $full_summ = view('cart.blocks.full_summ')->render();

        return [
            'header_cart' => $header_cart,
            'total' => $total,
            'summ' => $summ,
            'full_summ' => $full_summ,
            'item' => $item
        ];
    }

    public function postRemoveFromCart(Request $request)
    {
        $id = $request->get('id');
        Cart::remove($id);

        $sum = Cart::sum();

        $header_cart = view('blocks.header_cart')->render();
        $cart_values = view('blocks.cart_values', ['sum' => $sum])->render();

        return ['header_cart' => $header_cart, 'cart_values' => $cart_values];
    }

    public function postPurgeCart()
    {
        Cart::purge();

//        $header_cart = view('cart.index', ['items' => []])->render();

        return [];
    }

    //заявка в свободной форме
    public function postRequest(Request $request)
    {
        $data = $request->only(['name', 'phone', 'email', 'text']);
        $valid = Validator::make($data, [
            'name' => 'required',
            'phone' => 'required',
            'text' => 'required'
        ], [
            'name.required' => 'Не заполнено поле Имя',
            'phone.required' => 'Не заполнено поле Телефон',
            'text.required' => 'Не заполнено поле Сообщение',
        ]);

        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        } else {
            $feedback_data = [
                'type' => 1,
                'data' => $data
            ];
            $feedback = Feedback::create($feedback_data);
            Mail::send('mail.feedback', ['feedback' => $feedback], function ($message) use ($feedback) {
                $title = $feedback->id . ' | Заявка в свободной форме | Stal-Service';
                $message->from($this->fromMail, $this->fromName)
                    ->to(Settings::get('feedback_email'))
                    ->subject($title);
            });

            return ['success' => true];
        }
    }

    //написать нам
    public function postWriteback(Request $request)
    {
        $data = $request->only(['name', 'phone', 'text']);
        $valid = Validator::make($data, [
            'name' => 'required',
            'phone' => 'required',
        ], [
            'name.required' => 'Не заполнено поле Имя',
            'phone.required' => 'Не заполнено поле Телефон',
        ]);

        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        } else {
            $feedback_data = [
                'type' => 2,
                'data' => $data
            ];
            $feedback = Feedback::create($feedback_data);
            Mail::send('mail.feedback', ['feedback' => $feedback], function ($message) use ($feedback) {
                $title = $feedback->id . ' | Написать нам | Stal-Service';
                $message->from($this->fromMail, $this->fromName)
                    ->to(Settings::get('feedback_email'))
                    ->subject($title);
            });

            return ['success' => true];
        }
    }

    //заказать звонок
    public function postCallback(Request $request)
    {
        $data = $request->only(['name', 'phone', 'time']);
        $valid = Validator::make($data, [
            'name' => 'required',
            'phone' => 'required',
        ], [
            'name.required' => 'Не заполнено поле Имя',
            'phone.required' => 'Не заполнено поле Телефон',
        ]);

        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        } else {
            $feedback_data = [
                'type' => 3,
                'data' => $data
            ];
            $feedback = Feedback::create($feedback_data);
            Mail::send('mail.feedback', ['feedback' => $feedback], function ($message) use ($feedback) {
                $title = $feedback->id . ' | Заказать звонок | Stal-Service';
                $message->from($this->fromMail, $this->fromName)
                    ->to(Settings::get('feedback_email'))
                    ->subject($title);
            });

            return ['success' => true];
        }
    }

    //быстрый заказ
    public function postFastRequest(Request $request)
    {
        $data = $request->only(['name', 'phone']);
        $valid = Validator::make($data, [
            'name' => 'required',
            'phone' => 'required',
        ], [
            'name.required' => 'Не заполнено поле Имя',
            'phone.required' => 'Не заполнено поле Телефон',
        ]);

        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        } else {
            $feedback_data = [
                'type' => 4,
                'data' => $data
            ];
            $feedback = Feedback::create($feedback_data);
            Mail::send('mail.feedback', ['feedback' => $feedback], function ($message) use ($feedback) {
                $title = $feedback->id . ' | Быстрый заказ | Stal-Service';
                $message->from($this->fromMail, $this->fromName)
                    ->to(Settings::get('feedback_email'))
                    ->subject($title);
            });

            return ['success' => true];
        }
    }

    //остались вопросы?
    public function postQuestions(Request $request)
    {
        $data = $request->only(['phone']);
        $valid = Validator::make($data, [
            'phone' => 'required',
        ], [
            'phone.required' => 'Не заполнено поле Телефон',
        ]);

        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        } else {
            $feedback_data = [
                'type' => 5,
                'data' => $data
            ];
            $feedback = Feedback::create($feedback_data);
            Mail::send('mail.feedback', ['feedback' => $feedback], function ($message) use ($feedback) {
                $title = $feedback->id . ' | Отались вопросы | Stal-Service';
                $message->from($this->fromMail, $this->fromName)
                    ->to(Settings::get('feedback_email'))
                    ->subject($title);
            });

            return ['success' => true];
        }
    }

    //заявка в свободной форме
    public function postContactUs(Request $request)
    {
        $data = $request->only(['name', 'phone', 'text']);
        $valid = Validator::make($data, [
            'name' => 'required',
            'phone' => 'required',
            'text' => 'required'
        ], [
            'name.required' => 'Не заполнено поле Имя',
            'phone.required' => 'Не заполнено поле Телефон',
            'text.required' => 'Не заполнено поле Сообщение',
        ]);

        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        } else {
            $feedback_data = [
                'type' => 6,
                'data' => $data
            ];
            $feedback = Feedback::create($feedback_data);
            Mail::send('mail.feedback', ['feedback' => $feedback], function ($message) use ($feedback) {
                $title = $feedback->id . ' | Свяжитесь с нами | Stal-Service';
                $message->from($this->fromMail, $this->fromName)
                    ->to(Settings::get('feedback_email'))
                    ->subject($title);
            });

            return ['success' => true];
        }
    }

    //ОФОРМЛЕНИЕ ЗАКАЗА
    public function postOrder(Request $request)
    {
        $data = $request->only([
            'name',
            'phone',
            'email',
            'user',
            'text',
            'inn',
            'company',
            'address',
            'timing',
            'delivery_method',
            'summ',
            'total_weight',
//            'payment_method',
        ]);
        $file = $data['file'] = $request->file('file');

        $messages = array(
            'email.required' => 'Не указан ваш e-mail адрес!',
            'email.email' => 'Не корректный e-mail адрес!',
            'name.required' => 'Не заполнено поле Имя',
            'phone.required' => 'Не заполнено поле Телефон',
            'delivery_method.required' => 'Не выбран способ доставки',
//            'payment_method.required'  => 'Не выбран способ оплаты',
        );

        $valid = Validator::make($data, [
            'user' => 'required|numeric',
            'name' => 'required',
            'phone' => 'required',
            'inn' => 'required_if:user,0',
            'company' => 'required_if:user,0',
            'delivery_method' => 'required',
            'summ' => 'required|min:1',
            'total_weight'     => 'required',
            'address' => 'required_if:delivery_method,0',
            'file' => 'nullable|max:5120|mimes:jpg,jpeg,png,pdf,doc,docs,xls,xlsx',
        ], $messages);
        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        }
        if ($file) {
            $file_name = md5(uniqid(rand(), true)) . '.' . $file->getClientOriginalExtension();
            $file->move(base_path() . Order::UPLOAD_PATH, $file_name);
            $data['file'] = $file_name;
        }

        $order = Order::create($data);
        $items = Cart::all();
        $summ = 0;
        $total_weight = 0;
        $all_count = 0;
        foreach ($items as $item) {
            if($item['measure'] == 'м2') {
                if($item['factor_m2_weight']) {
                    $item['count_weight'] = round($item['factor_m2_weight'] * $item['count_per_tonn'],2);
                } else {
                    $item['factor_m2'] = $item['count_weight'];
                    $item['count_weight'] = null;
                }
            }

            $order->products()->attach($item['id'], [
                'count' => $item['count_per_tonn'],
                'm2' => $item['factor_m2'],
                'weight' => round($item['count_weight'], 2),
                'price' => Product::fullPrice($item['price']),
            ]);
            $summ += $item['measure'] == 'т' ? $item['count_weight'] * Product::fullPrice($item['price']) :
                $item['count_per_tonn'] * $item['factor_m2'] * Product::fullPrice($item['price']);
            $total_weight += round($item['count_weight'], 2);
            $all_count += $item['count_per_tonn'];
        }
        $order->update(['summ' => $summ, 'total_weight' => $total_weight]);

//        $data['total_sum'] = Cart::getRawTotalSum();
//        $order = Order::create($data);
//        $cart = Cart::getCart();
//        foreach ($cart as $item){
//            $product = array_get($item, 'model');
//            $product->update(['order_count' => $product->order_count +1]);
//            $size = array_get($item, 'size') ? array_get($item, 'size'): $product->param_size;
//            $order_item_data = [
//                'order_id'	=> $order->id,
//                'product_id'	=> array_get($item, 'id'),
//                'product_name'	=> $product->name,
//                'size'	=> $size,
//                'count'	=> array_get($item, 'count'),
//                'price'	=> array_get($item, 'price'),
//            ];
//            OrderItem::create($order_item_data);
//        }

//        if($data['payment_method'] == 3) {
//            return ['success' => true, 'redirect' => route('pay.order', ['id' => $order->id])];
//        }

        Mail::send('mail.new_order', ['order' => $order], function ($message) use ($order) {
            $title = $order->id . ' | Новый заказ | Stal-Service';
            $message->from($this->fromMail, $this->fromName)
                ->to(Settings::get('feedback_email'))
                ->subject($title);
        });

        Cart::purge();

        return ['success' => true, 'redirect' => url('/order-success', ['id' => $order->id])];
    }

    //РАБОТА С ГОРОДАМИ
    public function postSetCity()
    {
        $city_id = Request::get('city_id');
        $city = City::find($city_id);
        session(['change_city' => true]);
        if ($city) {
            $result = [
                'success' => true,
            ];
            session(['city_alias' => $city->alias]);

            return response(json_encode($result))->withCookie(cookie('city_id', $city->id));
        } elseif ($city_id == 0) {
            $result = [
                'success' => true,
            ];
            session(['city_alias' => '']);

            return response(json_encode($result))->withCookie(cookie('city_id', 0));
        }

        return ['success' => false, 'msg' => 'Город не найден'];
    }

    public function postGetCorrectRegionLink()
    {
        $city_id = Request::get('city_id');
        $city = City::find($city_id);
        $cur_url = Request::get('cur_url');

        if ($cur_url != '/') {
            $url = $cur_url;
            $path = explode('/', $cur_url);
            $cities = getCityAliases();
            /* проверяем - региональная ссылка или федеральная */
            if (in_array($path[0], $cities)) {
                if ($city) {
                    $path[0] = $city->alias;
                } else {
                    array_shift($path);
                }
            } else {
                if ($city && !in_array($path[0], Page::$excludeRegionAlias)) {
                    array_unshift($path, $city->alias);
                }
            }
            $url = '/' . implode('/', $path);
        } else { //Если на главной
//			if($city){
//				$url = '/' . $city->alias;
//			} else {
//				$url = $cur_url;
//			}
            $url = '/';
        }

        return ['redirect' => $url];
    }

    public function showCitiesPopup()
    {
        $cities = City::query()->orderBy('name')
            ->get(['id', 'alias', 'name', DB::raw('LEFT(name,1) as letter')]);
        $citiesArr = [];
        if (count($cities)) {
            foreach ($cities as $city) {
                $citiesArr[$city->letter][] = $city; //Группировка по первой букве
            }
        }

        $mainCities = City::query()->orderBy('name')
            ->whereIn('id', [
                3, // msk
                5, //spb
            ])->get(['id', 'alias', 'name']);
        $curUrl = url()->previous() ?: '/';
        $curUrl = str_replace(url('/') . '/', '', $curUrl);

        $current_city = SiteHelper::getCurrentCity();

        return view('blocks.popup_cities', [
            'cities' => $citiesArr,
            'mainCities' => $mainCities,
            'curUrl' => $curUrl,
            'current_city' => $current_city,
        ]);
    }

    public function search(Request $request)
    {
        $data = $request->only(['search']);

        $items = null;

        $page = Page::getByPath(['search']);
        $bread = $page->getBread();

        return [
            'success' => true,
            'redirect' => url('/search', [
                'bread' => $bread,
                'items' => $items,
                'data' => $data,
            ])];

//        return view('search.index', [
//            'bread' => $bread,
//            'items' => $items,
//            'data' => $data,
//        ]);

    }

    public function changeProductsPerPage(Request $request)
    {
        $count = $request->only('num');

        $setting = Setting::find(9);
        if ($setting) {
            $setting->value = $count['num'];
            $setting->save();
            return ['result' => true];
        } else {
            return ['result' => false];
        }
    }

    public function postSetView($view)
    {
        $view = $view == 'list' ? 'list' : 'grid';
        session(['catalog_view' => $view]);

        return ['success' => true];
    }

    public function postUpdateFilter(Request $request)
    {
        $column1 = $request->get('column1');
        $column2 = $request->get('column2');
        $category_id = $request->get('category_id');
        $filter_name1 = $request->get('filter_name1');
        $filter_name2 = $request->get('filter_name2');

        \Debugbar::log($column1);
        \Debugbar::log($column2);
        \Debugbar::log($category_id);
        \Debugbar::log($filter_name1);
        \Debugbar::log($filter_name2);

        $category = Catalog::find($category_id);

        if ($category->parent_id !== 0) {
            $root = $category->findRootCategory($category->parent_id);
        } else {
            $root = $category;
        }

        if(!$column1 && !$column2) {
            if ($category->parent_id == 0) {
                $ids = $category->getRecurseChildrenIds();
                $items = Product::public()->whereIn('catalog_id', $ids)
                    ->orderBy('name', 'asc')->paginate(10);
            } else {
                $items = $category->products()->paginate(10);
            }
        } else {
            if ($category->parent_id == 0) {
                $ids = $category->getRecurseChildrenIds();
                $items = Product::public()->whereIn('catalog_id', $ids)
                    ->where($filter_name1, '=', $column1)
                    ->orderBy('name', 'asc')
                    ->paginate(10);
            } else {
                $items = $category->products()->where($filter_name1, '=', 100)->paginate(10);
            }
        }

        $filters = $root->filters()->get();
        $sort = [];
        foreach ($filters as $filter) {
            if ($ids) {
                $sort[$filter->alias] = Product::public()->whereIn('catalog_id', $ids)
                    ->orderBy($filter->alias, 'asc')
                    ->groupBy($filter->alias)
                    ->distinct()
                    ->pluck($filter->alias)
                    ->all();
            } else {
                $sort[$filter->alias] = Product::public()->where('catalog_id', $category->id)
                    ->orderBy($filter->alias, 'asc')
                    ->groupBy($filter->alias)
                    ->distinct()
                    ->pluck($filter->alias)
                    ->all();
            }
        }

//        $list = view('catalog.views.list', [
//            'items' => $items,
//            'category' => $category,
//            'filters' => $filters,
//            'sort' => $sort,
//            'root' => $root,
//            'per_page' => 10,
//        ])->render();

        $paginate = view('catalog.views.paginate', ['items' => $items])->render();

        $list = [];
        foreach ($items as $item) {
            $list[] = view('catalog.list_row', [
                'item' => $item,
                'filters' => $filters,
                'sort' => $sort,
                'root' => $root,
                'per_page' => 10,
            ])->render();
        }

        return ['success' => true, 'list' => $list, 'paginate' => $paginate];

    }

}
