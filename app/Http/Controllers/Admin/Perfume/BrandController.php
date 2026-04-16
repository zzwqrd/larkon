<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeBrand;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    use ResponseTrait;

    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of brands.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $brands = PerfumeBrand::withCount('products')->orderBy('priority')->paginate(20);
            return response()->json(['html' => view('admin.perfume.brands.table', compact('brands'))->render()]);
        }

        return view('admin.perfume.brands.index');
    }

    /**
     * Show the form for creating a new brand.
     */
    public function create(): View
    {
        return view('admin.perfume.brands.create');
    }

    /**
     * Store a newly created brand in storage.
     */
    public function store(Request $request)
    {
        // Auto-fix website missing protocol
        if ($request->filled('website') && !preg_match('/^https?:\/\//i', $request->website)) {
            $request->merge(['website' => 'https://' . ltrim($request->website, '/')]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'country_of_origin' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:1700|max:2100',
            'website' => 'nullable|url',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $brand = new PerfumeBrand();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $brand->uploadFile($request->file('logo'), 'perfume/brands');
        }

        $brand = PerfumeBrand::create($validated);

        $this->activityLog->log('admin.logs.create_perfume_brand');

        return Response::json(['status' => 'success', 'msg' => __('admin.created'), 'url' => route('admin.perfume.brands.index')]);
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit($id): View
    {
        $brand = PerfumeBrand::findOrFail($id);
        return view('admin.perfume.brands.edit', compact('brand'));
    }

    /**
     * Update the specified brand in storage.
     */
    public function update(Request $request, $id)
    {
        $brand = PerfumeBrand::findOrFail($id);

        if ($request->filled('website') && !preg_match('/^https?:\/\//i', $request->website)) {
            $request->merge(['website' => 'https://' . ltrim($request->website, '/')]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'country_of_origin' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:1700|max:2100',
            'website' => 'nullable|url',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $brand->uploadFile($request->file('logo'), 'perfume/brands');
        }

        $brand->update($validated);

        $this->activityLog->log('admin.logs.update_perfume_brand');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated'), 'url' => route('admin.perfume.brands.index')]);
    }

    /**
     * Display the specified brand.
     */
    public function show($id): View
    {
        $brand = PerfumeBrand::with('products')->findOrFail($id);
        return view('admin.perfume.brands.show', compact('brand'));
    }

    /**
     * Remove the specified brand from storage.
     */
    public function destroy($id)
    {
        $brand = PerfumeBrand::findOrFail($id);
        $brand->delete();

        $this->activityLog->log('admin.logs.delete_perfume_brand');

        return Response::json(['id' => $id]);
    }

    /**
     * Toggle brand active status.
     */
    public function toggle($id)
    {
        $brand = PerfumeBrand::findOrFail($id);
        $brand->update(['is_active' => !$brand->is_active]);
        
        $this->activityLog->log('admin.logs.update_perfume_brand');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated')]);
    }

    /**
     * Bulk delete brands.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No IDs provided']);

        PerfumeBrand::whereIn('id', $ids)->delete();
        $this->activityLog->log('admin.logs.delete_perfume_brand');

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
