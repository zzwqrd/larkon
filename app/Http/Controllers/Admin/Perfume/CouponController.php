<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeCoupon;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class CouponController extends Controller
{
    use ResponseTrait;

    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of coupons.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $coupons = PerfumeCoupon::orderBy('created_at', 'desc')->paginate(20);
            return response()->json(['html' => view('admin.perfume.coupons.table', compact('coupons'))->render()]);
        }

        return view('admin.perfume.coupons.index');
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create(): View
    {
        return view('admin.perfume.coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:perfume_coupons,code',
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');
        $validated['applies_to_shipping'] = $request->has('applies_to_shipping');

        PerfumeCoupon::create($validated);

        $this->activityLog->log('admin.logs.create_perfume_coupon');

        return Response::json(['status' => 'success', 'msg' => __('admin.created'), 'url' => route('admin.perfume.coupons.index')]);
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit($id): View
    {
        $coupon = PerfumeCoupon::findOrFail($id);
        return view('admin.perfume.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, $id)
    {
        $coupon = PerfumeCoupon::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|unique:perfume_coupons,code,' . $id,
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->has('is_active');
        $validated['applies_to_shipping'] = $request->has('applies_to_shipping');

        $coupon->update($validated);

        $this->activityLog->log('admin.logs.update_perfume_coupon');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated'), 'url' => route('admin.perfume.coupons.index')]);
    }

    /**
     * Display the specified coupon.
     */
    public function show($id): View
    {
        // Assuming relationship with orders if needed in future
        $coupon = PerfumeCoupon::findOrFail($id);
        return view('admin.perfume.coupons.show', compact('coupon'));
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy($id)
    {
        $coupon = PerfumeCoupon::findOrFail($id);
        $coupon->delete();

        $this->activityLog->log('admin.logs.delete_perfume_coupon');

        return Response::json(['id' => $id]);
    }

    /**
     * Toggle coupon active status.
     */
    public function toggle($id)
    {
        $coupon = PerfumeCoupon::findOrFail($id);
        $coupon->update(['is_active' => !$coupon->is_active]);
        
        $this->activityLog->log('admin.logs.update_perfume_coupon');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated')]);
    }

    /**
     * Bulk delete coupons.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No IDs provided']);

        PerfumeCoupon::whereIn('id', $ids)->delete();
        $this->activityLog->log('admin.logs.delete_perfume_coupon');

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
