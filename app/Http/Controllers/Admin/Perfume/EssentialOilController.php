<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeEssentialOil;
use App\Models\PerfumeOilCategory;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class EssentialOilController extends Controller
{
    use ResponseTrait;

    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of essential oils.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $oils = PerfumeEssentialOil::with('category')->orderBy('priority')->paginate(20);
            return response()->json(['html' => view('admin.perfume.essential_oils.table', compact('oils'))->render()]);
        }

        return view('admin.perfume.essential_oils.index');
    }

    /**
     * Show the form for creating a new oil.
     */
    public function create(): View
    {
        $categories = PerfumeOilCategory::where('is_active', true)->orderBy('priority')->get();
        return view('admin.perfume.essential_oils.create', compact('categories'));
    }

    /**
     * Store a newly created oil in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'oil_category_id' => 'nullable|exists:perfume_oil_categories,id',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'price_per_unit' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'stock_quantity' => 'nullable|integer|min:0',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $oil = new PerfumeEssentialOil();

        if ($request->hasFile('image')) {
            $validated['image'] = $oil->uploadFile($request->file('image'), 'perfume/oils');
        }

        PerfumeEssentialOil::create($validated);

        $this->activityLog->log('admin.logs.create_perfume_essential_oil');

        return Response::json(['status' => 'success', 'msg' => __('admin.created'), 'url' => route('admin.perfume.essential-oils.index')]);
    }

    /**
     * Show the form for editing the specified oil.
     */
    public function edit($id): View
    {
        $oil = PerfumeEssentialOil::findOrFail($id);
        $categories = PerfumeOilCategory::where('is_active', true)->orderBy('priority')->get();
        return view('admin.perfume.essential_oils.edit', compact('oil', 'categories'));
    }

    /**
     * Update the specified oil in storage.
     */
    public function update(Request $request, $id)
    {
        $oil = PerfumeEssentialOil::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'oil_category_id' => 'nullable|exists:perfume_oil_categories,id',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'price_per_unit' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'stock_quantity' => 'nullable|integer|min:0',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $oil->uploadFile($request->file('image'), 'perfume/oils');
        }

        $oil->update($validated);

        $this->activityLog->log('admin.logs.update_perfume_essential_oil');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated'), 'url' => route('admin.perfume.essential-oils.index')]);
    }

    /**
     * Display the specified oil.
     */
    public function show($id): View
    {
        $oil = PerfumeEssentialOil::with('category')->findOrFail($id);
        return view('admin.perfume.essential_oils.show', compact('oil'));
    }

    /**
     * Remove the specified oil from storage.
     */
    public function destroy($id)
    {
        $oil = PerfumeEssentialOil::findOrFail($id);
        $oil->delete();

        $this->activityLog->log('admin.logs.delete_perfume_essential_oil');

        return Response::json(['id' => $id]);
    }

    /**
     * Toggle oil active status.
     */
    public function toggle($id)
    {
        $oil = PerfumeEssentialOil::findOrFail($id);
        $oil->update(['is_active' => !$oil->is_active]);
        
        $this->activityLog->log('admin.logs.update_perfume_essential_oil');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated')]);
    }

    /**
     * Bulk delete oils.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No IDs provided']);

        PerfumeEssentialOil::whereIn('id', $ids)->delete();
        $this->activityLog->log('admin.logs.delete_perfume_essential_oil');

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
