<?php namespace Fanky\Admin;

use Fanky\Admin\Models\Product;
use Session;

class Cart {

	private static $key = 'cart';

	public static function push($item)
	{
		return Session::push(self::$key, $item);
	}

	public static function pull($key)
	{
		return Session::pull(self::$key.'.'.$key);
	}

	public static function all()
	{
		$res = Session::get(self::$key, []);
		return is_array($res) ? $res : [];
	}

	public static function lists($field)
	{
		$list = [];
		foreach (self::all() as $key => $item) {
			if (isset($item[$field])) $list[] = $item[$field];
		}
		return $list;
	}

	public static function sum()
	{
		$ids = self::lists('id');
		if (empty($ids)) return 0;

		$products = Product::whereIn('id', $ids)->get();
		$sum = 0;
		foreach ($products as $item) {
			$sum += $item->price;
		}
		return $sum;
	}
}
