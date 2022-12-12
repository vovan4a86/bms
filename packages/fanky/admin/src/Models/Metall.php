<?php namespace Fanky\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Thumb;
use Carbon\Carbon;

/**
 * @property mixed category_id
 */
class Metall extends Product {
    protected $_parents = [];

    protected $guarded = ['id'];

    const UPLOAD_PATH = '/public/uploads/metall/';
    const UPLOAD_URL = '/uploads/metall/';
    public $type = 'metall';

    public static $thumbs = [
        1 => '210x243',
    ];

    protected $dates = [
        'parse_date'
    ];


    public function catalog() {
        return $this->belongsTo(Catalog::class,'category_id');
    }

}
