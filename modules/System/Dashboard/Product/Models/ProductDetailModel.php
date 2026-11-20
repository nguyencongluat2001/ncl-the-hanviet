<?php

namespace Modules\System\Dashboard\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDetailModel extends Model
{
    protected $table = 'product_details';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'product_id', 'code', 'sort_order', 'name_chu_han', 'name_han_viet',
        'name_quan_hoa', 'decision', 'image', 'created_at',
    ];

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }
}
