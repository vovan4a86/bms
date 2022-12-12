<?php namespace Fanky\Admin;

use Fanky\Admin\Models\Product;
use Session;

class Cart {

	private static $key = 'cart';

	public static function add($item){
		$cart = self::all();
        $cur_factor = $item['factor'] / 1000;

		if($item['measure'] === 'т') {
            if(!$item['count_weight'] || $item['count_weight'] == null || $item['count_weight'] == 0) {
            $count_per_tonn = 0;
            $count_weight = 0;
                do {
                    $count_weight += $cur_factor;
                    $count_per_tonn++;
                } while ($count_weight < 1);
            $item['count_weight'] = $count_weight;
            $item['count_per_tonn'] = $count_per_tonn;
            }
        }
        if($item['measure'] === 'м2') {
            if(!$item['count_weight'] || $item['count_weight'] == null || $item['count_weight'] == 0) {
                $dlina = 1;
                if($item['dlina']) $dlina = preg_replace('/[А-Яа-я]/', '', $item['dlina']);
                $shirina = 1;
                if($item['shirina']) $shirina = preg_replace('/[А-Яа-я]/', '', $item['shirina']);
                $count_weight = $dlina * $shirina;
                if($item['factor_m2']) $count_weight = $item['factor_m2'];
            } else {
//                $factor_m2_weight = $item['factor_m2_weight'] ? $item['factor_m2_weight'] * 1000 : 1;
                $count_weight = $item['count_weight'];
            }
            $item['count_weight'] = $count_weight;
            $item['count_per_tonn'] = 1;
        }

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

	public static function updateCount($id, $count, $weight){
		$cart = self::all();
		if(isset($cart[$id])){
			$cart[$id]['count_per_tonn'] = $count;
            $cart[$id]['count_weight'] = $weight;
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

	/**
	 * сумма всех в корзине
	 * @return int
	 */
	public static function sum() {
		$cart = self::all();
		$sum = 0;
		foreach ($cart as $item) {
            if($item['price'] != 0)
            $sum += Product::fullPrice($item['price']) * $item['count_weight'];
		}
		return number_format(round($sum, 2), 2, ',', ' ');
	}

    public static function total_weight() {
        $cart = self::all();
        $total = 0;
        foreach ($cart as $item) {
            if($item['measure'] == 'м2') {
                $factor_m2_weight = $item['factor_m2_weight'] ? $item['factor_m2_weight'] * 1000 : 1;
                $total += $item['count_weight'] * $factor_m2_weight * $item['factor'] / 1000;
            } else {
                $total += $item['count_weight'] ? : 0;
            }
        }
        return number_format(round($total, 2), 2, ',', ' ');
    }
}
