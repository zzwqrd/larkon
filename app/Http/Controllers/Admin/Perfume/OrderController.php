<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeOrder;
use App\Models\PerfumeOrderStatusHistory;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class OrderController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of orders.
     * Ported and enhanced with professional AJAX support and activity logs.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = PerfumeOrder::with('items')
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->latest()
                ->paginate(20);

            return response()->json([
                'html' => view('admin.perfume.orders.table', compact('orders'))->render()
            ]);
        }

        return view('admin.perfume.orders.index');
    }

    public function show($id): View
    {
        $order = PerfumeOrder::with(['items.product', 'statusHistory.admin'])->findOrFail($id);
        return view('admin.perfume.orders.show', compact('order'));
    }

    /**
     * Update order status with professional auditing.
     */
    public function updateStatus(Request $request, $id)
    {
        $order = PerfumeOrder::findOrFail($id);

        $request->validate([
            'status' => 'required|in:new,processing,shipped,delivered,cancelled,returned',
            'note' => 'nullable|string',
            'notify_customer' => 'nullable|boolean',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        PerfumeOrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'note' => $request->note,
            'changed_by' => auth('admin')->id(),
            'notify_customer' => $request->has('notify_customer'),
        ]);

        (new ActivityLogService())->log('admin.logs.update_perfume_order_status');

        return response()->json(['status' => 'success', 'msg' => __('admin.updated')]);
    }

    public function destroy($id)
    {
        $order = PerfumeOrder::findOrFail($id);
        $order->delete();

        (new ActivityLogService())->log('admin.logs.delete_perfume_order');

        return response()->json(['success' => true, 'id' => $id]);
    }
}
