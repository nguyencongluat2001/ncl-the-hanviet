<?php

namespace Modules\System\Dashboard\Product\Services;

use Illuminate\Support\Str;
use Modules\Base\Service;
use Modules\System\Dashboard\Product\Models\ProductDetailModel;
use Modules\System\Dashboard\Product\Repositories\ProductDetailRepository;

class ProductDetailService extends Service
{
    public function repository()
    {
        return ProductDetailRepository::class;
    }

    public function listForProduct(string $productId, ?string $searchBy = null, ?string $search = null)
    {
        $query = ProductDetailModel::where('product_id', $productId);
        $columns = ['name_chu_han', 'name_quan_hoa', 'name_han_viet'];
        $search = trim((string) $search);

        if ($search !== '' && in_array($searchBy, $columns, true)) {
            $query->where($searchBy, 'like', '%' . $search . '%');
        }

        return $query->orderBy('sort_order')->orderBy('created_at')->get();
    }

    public function save(array $input, ?string $imageName = null): ProductDetailModel
    {
        $detail = !empty($input['id'])
            ? ProductDetailModel::where('product_id', $input['product_id'])->findOrFail($input['id'])
            : new ProductDetailModel();

        $detail->fill([
            'id' => $detail->exists ? $detail->id : (string) Str::uuid(),
            'product_id' => $input['product_id'],
            'code' => $input['code'] ?? null,
            'sort_order' => $input['sort_order'] ?? (ProductDetailModel::where('product_id', $input['product_id'])->max('sort_order') + 1),
            'name_chu_han' => $input['name_chu_han'] ?? null,
            'name_han_viet' => $input['name_han_viet'] ?? null,
            'name_quan_hoa' => $input['name_quan_hoa'] ?? null,
            'decision' => $input['decision'] ?? null,
            'created_at' => $detail->exists ? $detail->created_at : now(),
        ]);
        if ($imageName !== null) {
            $detail->image = $imageName;
        }
        $detail->save();

        return $detail;
    }

    public function deleteForProduct(string $productId, string $id): ?string
    {
        $detail = ProductDetailModel::where('product_id', $productId)->findOrFail($id);
        $image = $detail->image;
        $detail->delete();

        return $image;
    }
}
