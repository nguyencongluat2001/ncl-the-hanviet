<?php

namespace Modules\System\Dashboard\Product\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\System\Dashboard\Product\Models\ProductDetailModel;

class ProductDetailSearchController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search_by' => ['nullable', 'in:name_chu_han,name_quan_hoa,name_han_viet'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $searchBy = $filters['search_by'] ?? 'name_chu_han';
        $search = trim($filters['search'] ?? '');
        $query = ProductDetailModel::with('product');

        if ($search !== '') {
            $query->where($searchBy, 'like', '%' . $search . '%');
        }

        $details = $query->orderBy('sort_order')->orderBy('created_at')->paginate(25)->withQueryString();

        return view('dashboard.UrlSearch.words', compact('details', 'searchBy', 'search'));
    }
}
