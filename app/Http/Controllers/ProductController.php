<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::query()->where('is_active', true);

        // Server-side Searching
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Server-side Sorting
        $sortColumn = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortColumn, $sortOrder);

        // Server-side Pagination
        $products = $query->paginate(10);

        // Handle AJAX Request for Table Updates
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('general.products._table', compact('products'))->render()
            ]);
        }

        return view('general.products.list', compact('products'));
    }

    /**
     * Delete a product via AJAX.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['success' => true, 'id' => $id]);
    }

    /**
     * Bulk Delete products via AJAX.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            Product::whereIn('id', $ids)->delete();
        }

        return response()->json(['success' => true]);
    }
}
