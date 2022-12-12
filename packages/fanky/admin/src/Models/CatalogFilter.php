<?php namespace Fanky\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogFilter extends Model {

    protected $table = 'catalog_filters';

    protected $fillable = ['id', 'catalog_id'];

    public $timestamps = false;

    public function catalog() {
        return $this->belongsToMany(Catalog::class);
    }
}
