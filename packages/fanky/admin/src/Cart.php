<?php namespace Fanky\Admin;

use Fanky\Admin\Models\Product;
use Session;

class Cart {

    private static $key = 'cart';

    public static function add($item) {
        $cart = self::all();

        $cart[$item['id']] = $item;
        Session::put(self::$key, $cart);
    }

    public static function remove($id) {
        $cart = self::all();
        unset($cart[$id]);
        Session::put(self::$key, $cart);
    }

    public static function ifInCart($id) {
        $cart = self::all();
        return isset($cart[$id]);
    }

    public static function updateCount($id, $count) {
        $cart = self::all();
        if (isset($cart[$id])) {
            if ($cart[$id]['measure'] == 'т') {
                $cart[$id]['weight'] = $count;
            } elseif ($cart[$id]['measure'] == 'кг') {
                $cart[$id]['weight'] = $count;
            } elseif ($cart[$id]['measure'] == 'м') {
                $cart[$id]['count'] = $count;
            } else {
                $cart[$id]['count'] = $count;
            }

            Session::put(self::$key, $cart);
        }
    }

    public static function purge() {
        Session::put(self::$key, []);
    }

    public static function all() {
        $res = Session::get(self::$key, []);
        return is_array($res) ? $res : [];
    }

    public static function sum(): int {
        $cart = self::all();
        $sum = 0;
        foreach ($cart as $item) {
            if ($item['measure'] == 'т') {
                $sum += $item['weight'] * $item['price'];
            } elseif ($item['measure'] == 'кг') {
                $sum += $item['weight'] * $item['price_per_kilo'];
            } elseif ($item['measure'] == 'шт') {
                $sum += $item['count'] * $item['price_per_item'];
            } elseif ($item['measure'] == 'м') {
                $sum += $item['count'] * $item['price_per_metr'];
            }
        }
        return round($sum);
    }

    public static function total_weight(): int {
        $cart = self::all();
        $total = 0;
        foreach ($cart as $item) {
            if ($item['measure'] == 'т') {
                $total += $item['weight'] * 1000;
            } elseif ($item['measure'] == 'кг') {
                $total += $item['weight'];
            } elseif ($item['measure'] == 'м') {
                $total += $item['weight'] * 1000;
            } else {
                $total += $item['weight'] * $item['count'];
            }
        }

        return round($total);
    }
}
