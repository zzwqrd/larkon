<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeOilCategory;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class OilCategoryController extends Controller
{
    use ResponseTrait;

    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of oil categories.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = PerfumeOilCategory::withCount('essentialOils')->orderBy('priority')->paginate(20);
            return response()->json(['html' => view('admin.perfume.oil_categories.table', compact('categories'))->render()]);
        }

        return view('admin.perfume.oil_categories.index');
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.perfume.oil_categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:2048',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $category = new PerfumeOilCategory();

        if ($request->hasFile('image')) {
            $validated['image'] = $category->uploadFile($request->file('image'), 'perfume/oil-categories');
        }
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $category->uploadFile($request->file('banner_image'), 'perfume/oil-categories');
        }

        PerfumeOilCategory::create($validated);

        $this->activityLog->log('admin.logs.create_perfume_oil_category');

        return Response::json(['status' => 'success', 'msg' => __('admin.created'), 'url' => route('admin.perfume.oil-categories.index')]);
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id): View
    {
        $category = PerfumeOilCategory::findOrFail($id);
        return view('admin.perfume.oil_categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = PerfumeOilCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'banner_image' => 'nullable|image|max:2048',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $category->uploadFile($request->file('image'), 'perfume/oil-categories');
        }
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $category->uploadFile($request->file('banner_image'), 'perfume/oil-categories');
        }

        $category->update($validated);

        $this->activityLog->log('admin.logs.update_perfume_oil_category');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated'), 'url' => route('admin.perfume.oil-categories.index')]);
    }

    /**
     * Display the specified category.
     */
    public function show($id): View
    {
        $category = PerfumeOilCategory::with('essentialOils')->findOrFail($id);
        return view('admin.perfume.oil_categories.show', compact('category'));
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = PerfumeOilCategory::findOrFail($id);
        $category->delete();

        $this->activityLog->log('admin.logs.delete_perfume_oil_category');

        return Response::json(['id' => $id]);
    }

    /**
     * Toggle category active status.
     */
    public function toggle($id)
    {
        $category = PerfumeOilCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        
        $this->activityLog->log('admin.logs.update_perfume_oil_category');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated')]);
    }

    /**
     * Bulk delete categories.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No IDs provided']);

        PerfumeOilCategory::whereIn('id', $ids)->delete();
        $this->activityLog->log('admin.logs.delete_perfume_oil_category');

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
