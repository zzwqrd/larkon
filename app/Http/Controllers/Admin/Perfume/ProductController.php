<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PerfumeCategory;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of products.
     * Ported and enhanced with Activity Logging and professional AJAX logic.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Professional Filtering logic
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.perfume.products.table', compact('products'))->render()
            ]);
        }

        return view('admin.perfume.products.index', compact('products'));
    }

    public function create()
    {
        $categories = PerfumeCategory::orderBy('priority')->get();
        return view('admin.perfume.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_en' => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'category_id'    => 'nullable|exists:perfume_categories,id',
            'image'          => 'nullable|image',
            'size'           => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($request->name_en ?? $request->name) . '-' . time();
        $validated['is_active'] = $request->has('is_active');

        $product = new Product();
        if ($request->hasFile('image')) {
            $validated['image'] = $product->uploadAllTyps($request->file('image'), 'perfume/products');
        }

        Product::create($validated);

        (new ActivityLogService())->log('admin.logs.create_product');

        return response()->json([
            'status' => 'success',
            'msg' => __('admin.created'),
            'url' => route('admin.perfume.products.index')
        ]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = PerfumeCategory::orderBy('priority')->get();
        return view('admin.perfume.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_en' => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'category_id'    => 'nullable|exists:perfume_categories,id',
            'image'          => 'nullable|image',
            'size'           => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $product->uploadAllTyps($request->file('image'), 'perfume/products');
        }

        $product->update($validated);

        (new ActivityLogService())->log('admin.logs.update_product');

        return response()->json([
            'status' => 'success',
            'msg' => __('admin.updated'),
            'url' => route('admin.perfume.products.index')
        ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        (new ActivityLogService())->log('admin.logs.delete_product');

        return response()->json(['success' => true, 'id' => $id]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Product::whereIn('id', $ids)->delete();
            (new ActivityLogService())->log('admin.logs.bulk_delete_products');
        }

        return response()->json(['success' => true]);
    }
}
