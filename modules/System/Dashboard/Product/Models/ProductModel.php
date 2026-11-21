<?php

namespace Modules\System\Dashboard\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\System\Dashboard\Blog\Models\BlogDetailModel;
use Modules\System\Dashboard\Blog\Models\BlogImagesModel;

class ProductModel extends Model
{
    protected $table = 'product';
    public $incrementing = false;
    public $timestamps = false;
    public $sortable = ['created_at'];

    protected $fillable = [
        'id',
        'user_id',
        'code',
        'name',
        'code_category',
        'trademark',
        'sale',
        'image',
        'price',
        'decision',
        'status',
        'created_at',
        'updated_at'
    ];

    public function wordDetails()
    {
        return $this->hasMany(ProductDetailModel::class, 'product_id');
    }

    public function filter($query, $param, $value)
    {
        switch ($param) {
            case 'search':
                return $query->where(function ($query) use ($value) {
                    $query->where('name', 'like', '%' . $value . '%')
                        ->orWhere('code', 'like', '%' . $value . '%')
                        ->orWhere('trademark', 'like', '%' . $value . '%');
                });
            case 'category':
                $query->where('code_category', $value);
                return $query;
            default:
                return $query;
        }
    }
    // public function detailBlog()
    // {
    //     return $this->hasOne(BlogDetailModel::class, 'code_blog', 'code_blog');
    // }
    // public function imageBlog()
    // {
    //     return $this->hasMany(BlogImagesModel::class, 'code_blog', 'code_blog');
    // }
}
