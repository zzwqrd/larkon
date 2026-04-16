<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeCategory;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the categories.
     * Ported from perfume_admin with professional AJAX support.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = PerfumeCategory::with('parent')->orderBy('priority')->paginate(10);
            return response()->json([
                'html' => view('admin.perfume.categories.table', compact('categories'))->render()
            ]);
        }

        return view('admin.perfume.categories.index');
    }

    public function create(): View
    {
        $parents = PerfumeCategory::whereNull('parent_id')->orderBy('priority')->get();
        return view('admin.perfume.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_en' => 'nullable|string',
            'image'          => 'nullable|image',
            'icon'           => 'nullable|string|max:255',
            'parent_id'      => 'nullable|exists:perfume_categories,id',
            'type'           => 'required|in:editorial,products',
            'priority'       => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($request->name_en ?? $request->name) . '-' . time();
        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_navbar'] = $request->has('show_in_navbar');

        $category = new PerfumeCategory();
        if ($request->hasFile('image')) {
            $validated['image'] = $category->uploadAllTyps($request->file('image'), 'perfume/categories');
        }

        PerfumeCategory::create($validated);

        (new ActivityLogService())->log('admin.logs.create_perfume_category');

        return response()->json([
            'status' => 'success', 
            'msg' => __('admin.created'), 
            'url' => route('admin.perfume.categories.index')
        ]);
    }

    public function edit($id): View
    {
        $category = PerfumeCategory::findOrFail($id);
        $parents = PerfumeCategory::whereNull('parent_id')->where('id', '!=', $id)->orderBy('priority')->get();

        return view('admin.perfume.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, $id)
    {
        $category = PerfumeCategory::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'description_en' => 'nullable|string',
            'image'          => 'nullable|image',
            'icon'           => 'nullable|string|max:255',
            'parent_id'      => 'nullable|exists:perfume_categories,id',
            'type'           => 'required|in:editorial,products',
            'priority'       => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['show_in_navbar'] = $request->has('show_in_navbar');

        if ($request->hasFile('image')) {
            $validated['image'] = $category->uploadAllTyps($request->file('image'), 'perfume/categories');
        }

        $category->update($validated);

        (new ActivityLogService())->log('admin.logs.update_perfume_category');

        return response()->json([
            'status' => 'success', 
            'msg' => __('admin.updated'), 
            'url' => route('admin.perfume.categories.index')
        ]);
    }

    public function destroy($id)
    {
        $category = PerfumeCategory::findOrFail($id);
        $category->delete();

        (new ActivityLogService())->log('admin.logs.delete_perfume_category');

        return response()->json(['id' => $id]);
    }

    public function toggle($id)
    {
        $category = PerfumeCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        
        (new ActivityLogService())->log('admin.logs.update_perfume_category');

        return response()->json(['status' => 'success', 'msg' => __('admin.updated')]);
    }
}
