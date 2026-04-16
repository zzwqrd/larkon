<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeShippingMethod;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class ShippingController extends Controller
{
    use ResponseTrait;

    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of shipping methods.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $methods = PerfumeShippingMethod::orderBy('priority')->paginate(20);
            return response()->json(['html' => view('admin.perfume.shipping.table', compact('methods'))->render()]);
        }

        return view('admin.perfume.shipping.index');
    }

    /**
     * Show the form for creating a new shipping method.
     */
    public function create(): View
    {
        return view('admin.perfume.shipping.create');
    }

    /**
     * Store a newly created shipping method in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'base_cost' => 'required|numeric|min:0',
            'free_shipping_above' => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:1',
            'type' => 'required|in:local,international,both',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $method = new PerfumeShippingMethod();

        if ($request->hasFile('logo')) {
            $validated['logo'] = $method->uploadFile($request->file('logo'), 'perfume/shipping');
        }

        PerfumeShippingMethod::create($validated);

        $this->activityLog->log('admin.logs.create_perfume_shipping');

        return Response::json(['status' => 'success', 'msg' => __('admin.created'), 'url' => route('admin.perfume.shipping.index')]);
    }

    /**
     * Show the form for editing the specified shipping method.
     */
    public function edit($id): View
    {
        $method = PerfumeShippingMethod::findOrFail($id);
        return view('admin.perfume.shipping.edit', compact('method'));
    }

    /**
     * Update the specified shipping method in storage.
     */
    public function update(Request $request, $id)
    {
        $method = PerfumeShippingMethod::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'base_cost' => 'required|numeric|min:0',
            'free_shipping_above' => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:1',
            'type' => 'required|in:local,international,both',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $method->uploadFile($request->file('logo'), 'perfume/shipping');
        }

        $method->update($validated);

        $this->activityLog->log('admin.logs.update_perfume_shipping');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated'), 'url' => route('admin.perfume.shipping.index')]);
    }

    /**
     * Display the specified shipping method.
     */
    public function show($id): View
    {
        $method = PerfumeShippingMethod::findOrFail($id);
        return view('admin.perfume.shipping.show', compact('method'));
    }

    /**
     * Remove the specified shipping method from storage.
     */
    public function destroy($id)
    {
        $method = PerfumeShippingMethod::findOrFail($id);
        $method->delete();

        $this->activityLog->log('admin.logs.delete_perfume_shipping');

        return Response::json(['id' => $id]);
    }

    /**
     * Toggle shipping method active status.
     */
    public function toggle($id)
    {
        $method = PerfumeShippingMethod::findOrFail($id);
        $method->update(['is_active' => !$method->is_active]);
        
        $this->activityLog->log('admin.logs.update_perfume_shipping');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated')]);
    }

    /**
     * Bulk delete shipping methods.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No IDs provided']);

        PerfumeShippingMethod::whereIn('id', $ids)->delete();
        $this->activityLog->log('admin.logs.delete_perfume_shipping');

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
