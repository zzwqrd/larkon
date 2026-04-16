<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeSize;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class SizeController extends Controller
{
    use ResponseTrait;

    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of sizes.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Note: withCount('variants') can be added later when variants are fully ported
            $sizes = PerfumeSize::orderBy('priority')->paginate(20);
            return response()->json(['html' => view('admin.perfume.sizes.table', compact('sizes'))->render()]);
        }

        return view('admin.perfume.sizes.index');
    }

    /**
     * Show the form for creating a new size.
     */
    public function create(): View
    {
        return view('admin.perfume.sizes.create');
    }

    /**
     * Store a newly created size in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'volume_ml' => 'nullable|integer|min:0',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        PerfumeSize::create($validated);

        $this->activityLog->log('admin.logs.create_perfume_size');

        return Response::json(['status' => 'success', 'msg' => __('admin.created'), 'url' => route('admin.perfume.sizes.index')]);
    }

    /**
     * Show the form for editing the specified size.
     */
    public function edit($id): View
    {
        $size = PerfumeSize::findOrFail($id);
        return view('admin.perfume.sizes.edit', compact('size'));
    }

    /**
     * Update the specified size in storage.
     */
    public function update(Request $request, $id)
    {
        $size = PerfumeSize::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'volume_ml' => 'nullable|integer|min:0',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $size->update($validated);

        $this->activityLog->log('admin.logs.update_perfume_size');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated'), 'url' => route('admin.perfume.sizes.index')]);
    }

    /**
     * Display the specified size.
     */
    public function show($id): View
    {
        $size = PerfumeSize::findOrFail($id);
        return view('admin.perfume.sizes.show', compact('size'));
    }

    /**
     * Remove the specified size from storage.
     */
    public function destroy($id)
    {
        $size = PerfumeSize::findOrFail($id);
        $size->delete();

        $this->activityLog->log('admin.logs.delete_perfume_size');

        return Response::json(['id' => $id]);
    }

    /**
     * Toggle size active status.
     */
    public function toggle($id)
    {
        $size = PerfumeSize::findOrFail($id);
        $size->update(['is_active' => !$size->is_active]);
        
        $this->activityLog->log('admin.logs.update_perfume_size');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated')]);
    }

    /**
     * Bulk delete sizes.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No IDs provided']);

        PerfumeSize::whereIn('id', $ids)->delete();
        $this->activityLog->log('admin.logs.delete_perfume_size');

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
