<?php

namespace Modules\System\Dashboard\Product\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\System\Dashboard\Product\Models\ProductModel;
use Modules\System\Dashboard\Product\Services\ProductDetailService;

class ProductDetailController extends Controller
{
    private const IMAGE_DIRECTORY = 'file-image-client/product-details';

    public function __construct(private ProductDetailService $detailService)
    {
    }

    public function index(Request $request)
    {
        $filters = $request->validate([
            'search_by' => ['nullable', 'in:name_chu_han,name_quan_hoa,name_han_viet'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);
        $product = ProductModel::findOrFail($request->query('product_id'));
        return view('dashboard.product.word.index', [
            'product' => $product,
            'details' => $this->detailService->listForProduct(
                $product->id,
                $filters['search_by'] ?? 'name_han_viet',
                $filters['search'] ?? null
            ),
            'searchBy' => $filters['search_by'] ?? 'name_han_viet',
            'search' => $filters['search'] ?? '',
        ]);
    }

    public function form(Request $request)
    {
        $product = ProductModel::findOrFail($request->input('product_id'));
        $detail = null;
        if ($request->filled('id')) {
            $detail = $this->detailService->where('product_id', $product->id)->where('id', $request->input('id'))->firstOrFail();
        }
        $returnToAll = $request->boolean('return_to_all');
        return view('dashboard.product.word.form', compact('product', 'detail', 'returnToAll'));
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'uuid', 'exists:product,id'],
            'id' => ['nullable', 'uuid'],
            'code' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
            'name_chu_han' => ['nullable', 'string', 'max:255'],
            'name_han_viet' => ['required', 'string', 'max:255'],
            'name_quan_hoa' => ['nullable', 'string', 'max:255'],
            'decision' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
        ]);

        $oldImage = null;
        if (!empty($validated['id'])) {
            $oldImage = $this->detailService->where('product_id', $validated['product_id'])->where('id', $validated['id'])->value('image');
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = (string) Str::uuid() . '.' . $image->extension();
            File::ensureDirectoryExists(public_path(self::IMAGE_DIRECTORY));
            $image->move(public_path(self::IMAGE_DIRECTORY), $imageName);
        }

        $detail = $this->detailService->save($validated, $imageName);
        if ($imageName !== null && $oldImage) {
            File::delete(public_path(self::IMAGE_DIRECTORY . '/' . basename($oldImage)));
        }

        return response()->json(['success' => true, 'id' => $detail->id]);
    }

    public function delete(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'uuid', 'exists:product,id'],
            'id' => ['required', 'uuid'],
        ]);
        $image = $this->detailService->deleteForProduct($validated['product_id'], $validated['id']);
        if ($image) {
            File::delete(public_path(self::IMAGE_DIRECTORY . '/' . basename($image)));
        }

        return response()->json(['success' => true]);
    }
}
