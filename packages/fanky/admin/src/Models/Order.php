<?php namespace Fanky\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    protected $table = 'orders';

    protected $guarded = ['id'];

//    protected $fillable = ['delivery_method', 'payment_method', 'name', 'email', 'phone', 'new', 'summ'];

    const UPLOAD_PATH = '/public/uploads/orders/';
    const UPLOAD_URL  = '/uploads/orders/';

    public static $payer_type = [
        1	=> 'Частное лицо',
        2	=> 'Юридическое лицо',
    ];

    public static $payment = [
        1   => 'Наличный расчет',
        2	=> 'Безналичным расчет',
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

    public function delivery_method() {
        return $this->hasOne(DeliveryItem::class, 'id');
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

}
