<?php namespace Fanky\Admin;

use Fanky\Admin\Models\Product;
use Session;

class Cart {

	private static $key = 'cart';

	public static function add($item){
		$cart = self::all();

        $cart[$item['id']] = $item;
		Session::put(self::$key, $cart);
	}

	public static function remove($id){
		$cart = self::all();
		unset($cart[$id]);
		Session::put(self::$key, $cart);
	}

	public static function ifInCart($id){
		$cart = self::all();
		return isset($cart[$id]);
	}

	public static function updateCount($id, $count){
		$cart = self::all();
		if(isset($cart[$id])){
		    if($cart[$id]['measure'] == 'т') {
                $cart[$id]['weight'] = $count;
            } else {
                $cart[$id]['count'] = $count;
            }
            Session::put(self::$key, $cart);
		}
	}

	public static function purge(){
		Session::put(self::$key, []);
	}

	public static function all()
	{
		$res = Session::get(self::$key, []);
		return is_array($res) ? $res : [];
	}


	public static function sum(): int {
		$cart = self::all();
		$sum = 0;
		foreach ($cart as $item) {
            if($item['weight'] != 0)
                $sum += $item['weight'] * $item['price'];
            else {
                $sum += $item['count'] * $item['price_per_item'];
            }
		}
		return $sum;
	}

    public static function total_weight(): int {
        $cart = self::all();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['weight'] * 1000;
        }

        return $total;
    }
}
