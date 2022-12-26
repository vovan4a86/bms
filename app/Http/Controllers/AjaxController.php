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
    private $fromMail = 'info@bms.ru';
    private $fromName = 'БМС';

    //РАБОТА С КОРЗИНОЙ

    public function postAddToCart(Request $request): array {
        $id = $request->get('id');
        $size = $request->get('size');
        $weight = $request->get('weight');

        /** @var Product $product */
        $product = Product::find($id);
        if ($product) {
            $product_item = $product->toArray();
            $product_item['current_price'] = $product->price;
            $product_item['count'] = $size;
            $product_item['round_k'] = $product->round_k;
            if(!$weight) {
                $product_item['weight'] = $product_item['count'] *  $product_item['round_k'];
            } else {
                $product_item['weight'] = $weight;
            }

            $product_item['url'] = $product->url;
            Cart::add($product_item);
        }
        $header_cart = view('blocks.header_cart')->render();
//        $popup = view('blocks.product_added', $product_item)->render();

        return [
            'header_cart' => $header_cart,
        ];
    }

    public function postAddToCartPerItem(Request $request): array {
        $id = $request->get('id');
        $size = $request->get('size', 0);

        /** @var Product $product */
        $product = Product::find($id);
        if ($product) {
            $product_item = $product->toArray();
            $product_item['current_price'] = $product->price_per_item;
            $product_item['count'] = $size;
            $product_item['round_k'] = $product->round_k;
            $product_item['weight'] = 0;
            $product_item['url'] = $product->url;
            Cart::add($product_item);
        }
        $header_cart = view('blocks.header_cart')->render();
//        $popup = view('blocks.product_added', $product_item)->render();

        return [
            'header_cart' => $header_cart,
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

            Cart::add($product_item);
        }

        $popup = view('blocks.cart_popup', $product_item)->render();

        return ['cart_popup' => $popup];
    }

    public function postUpdateToCart(Request $request): array {
        $id = $request->get('id');
        $count = $request->get('count');

        Cart::updateCount($id, $count);

        $product = Product::find($id);

        $product_item = $product->toArray();
        $product_item['url'] = $product->url;

        if($product_item['measure'] == 'т') {
            $product_item['weight'] = $count;
        } else {
            $product_item['count'] = $count;
        }

        $cur_summ = view('cart.table_row_summ', ['item' => $product_item])->render();
        $order_total = view('cart.blocks.order_total')->render();

        return [
            'order_total' => $order_total,
            'cur_summ' => $cur_summ
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

    public function postPurgeCart() {
        Cart::purge();

        $header_cart = view('blocks.header_cart')->render();
        $order_total = view('cart.blocks.order_total', [
            'total_weight' => Cart::total_weight(),
            'sum' => Cart::sum()
        ])->render();

        return [
            'header_cart' => $header_cart,
            'order_total' => $order_total,
        ];
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
                $title = $feedback->id . ' | Заявка в свободной форме | БМС';
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
                $title = $feedback->id . ' | Написать нам | БМС';
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
                'type' => 3,
                'data' => $data
            ];
            $feedback = Feedback::create($feedback_data);
            Mail::send('mail.feedback', ['feedback' => $feedback], function ($message) use ($feedback) {
                $title = $feedback->id . ' | Заказать звонок | БМС>';
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
                $title = $feedback->id . ' | Быстрый заказ | БМС';
                $message->from($this->fromMail, $this->fromName)
                    ->to(Settings::get('feedback_email'))
                    ->subject($title);
            });

            return ['success' => true];
        }
    }

    //задать вопрос
    public function postQuestions(Request $request)
    {
        $data = $request->only(['name', 'phone', 'question']);
        $file = $request->file('file');
        $valid = Validator::make($data, [
            'name' => 'required',
            'phone' => 'required',
            'question' => 'required',
        ], [
            'name.required' => 'Не заполнено поле имя',
            'phone.required' => 'Не заполнено поле телефон',
            'question.required' => 'Не заполнено поле сообщение',
        ]);

        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        } else {
            if ($file) {
                $file_name = md5(uniqid(rand(), true)) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path(Feedback::UPLOAD_URL), $file_name);
                $data['file'] = '<a target="_blanc" href=\'' . Feedback::UPLOAD_URL . $file_name . '\'>' . $file_name . '</a>';
            }

            $feedback_data = [
                'type' => 5,
                'data' => $data
            ];

            $feedback = Feedback::create($feedback_data);
            Mail::send('mail.feedback', ['feedback' => $feedback], function ($message) use ($feedback) {
                $title = $feedback->id . ' | Задать вопрос | БМС';
                $message->from($this->fromMail, $this->fromName)
                    ->to(Settings::get('feedback_email'))
                    ->subject($title);
            });

            return ['success' => true];
        }
    }

    //свяжитесь с нами
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
                $title = $feedback->id . ' | Свяжитесь с нами | БМС';
                $message->from($this->fromMail, $this->fromName)
                    ->to(Settings::get('feedback_email'))
                    ->subject($title);
            });

            return ['success' => true];
        }
    }

    //ОФОРМЛЕНИЕ ЗАКАЗА
    public function postOrder(Request $request) {
        $data = $request->only([
            'payer_type',
            'name',
            'email',
            'phone',
            'company',
            'delivery',
            'payment',
        ]);

        $messages = array(
            'payer_type' => 'Не указан тип плательщика',
            'email.required' => 'Не указан ваш e-mail адрес!',
            'email.email' => 'Не корректный e-mail адрес!',
            'name.required' => 'Не заполнено поле Имя',
            'phone.required' => 'Не заполнено поле Телефон',
            'delivery.required' => 'Не выбран способ доставки',
            'payment.required' => 'Не выбран способ оплаты',
        );

        $valid = Validator::make($data, [
            'payer_type' => 'required',
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'company' => 'required_if:payer_type,2',
            'delivery' => 'required',
        ], $messages);
        if ($valid->fails()) {
            return ['errors' => $valid->messages()];
        }

        $data['summ'] = Cart::sum();
        $data['total_weight'] = Cart::total_weight();

        $order = Order::create($data);
        $items = Cart::all();

        foreach ($items as $item) {
            $order->products()->attach($item['id'], [
                'count' => $item['count'],
                'weight' => $item['weight'],
                'price' => $item['weight'] * $item['price']
            ]);
        }

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
            $title = $order->id . ' | Новый заказ | БМС';
            $message->from($this->fromMail, $this->fromName)
                ->to(Settings::get('feedback_email'))
                ->subject($title);
        });

        Cart::purge();

        return ['success' => true];
    }

    //РАБОТА С ГОРОДАМИ
    public function postSetCity($id) {
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

    public function setCity() {
        $id = Request::get('id');
        $city = City::findOrFail($id);
        if($city) {
            session(['current_city' => $city]);
        } else {
            session(['current_city' => null]);
        }
        return ['success' => true, 'city' => $city->name];
    }

    public function confirmCity() {
        session(['confirm_city' => true]);

        return ['success' => true];
    }

    public function unConfirmCity() {
        session(['confirm_city' => null]);

        return ['success' => true];
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
