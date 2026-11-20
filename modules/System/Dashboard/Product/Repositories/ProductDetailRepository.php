<?php

namespace Modules\System\Dashboard\Product\Repositories;

use Modules\Base\Repository;
use Modules\System\Dashboard\Product\Models\ProductDetailModel;

class ProductDetailRepository extends Repository
{
    public function model()
    {
        return ProductDetailModel::class;
    }
}
