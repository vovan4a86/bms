<?php namespace Fanky\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    protected $table = 'orders';

    protected $guarded = ['id'];

//    protected $fillable = ['delivery_method', 'payment_method', 'name', 'email', 'phone', 'new', 'summ'];

    const UPLOAD_PATH = '/public/uploads/orders/';
    const UPLOAD_URL  = '/uploads/orders/';

    public static $user_type = [
        0	=> 'Юридическое лицо',
        1	=> 'Частное лицо'
    ];

    public static $delivery_method = [
        0	=> 'Доставка',
        1	=> 'Самовывоз'
    ];

    public static $payment_method = [
        2	=> 'Безналичным платежом (по счёту)',
        0	=> 'По карте через терминалы салонов города',
        1	=> 'Наличными на месте оформления покупки',
        3   => 'По карте на сайте (тестовый режим)'
    ];

//    public function payment_order() {
//        return $this->hasOne(PaymentOrder::class)->first();
//    }

    public function products() {
        return $this->belongsToMany('Fanky\Admin\Models\Product')
            ->withPivot('count', 'weight', 'price', 'm2');
    }

    public function dateFormat($format = 'd.m.Y')
    {
        if (!$this->created_at) return null;
        return date($format, strtotime($this->created_at));
    }

//    public function getPaymentId($query) {
//        return $query->whereNew(1);
//    }
//
//	public function getPaymentStatus($query) {
//		return $query->whereNew(1);
//	}

    public function scopeNewOrder($query) {
        return $query->whereNew(1);
    }

    public function getDeliveryAttribute(){
        return self::$delivery_method[$this->delivery_method];
    }
    public function getPaymentAttribute(){
        return self::$payment_method[$this->payment_method];
    }
}
