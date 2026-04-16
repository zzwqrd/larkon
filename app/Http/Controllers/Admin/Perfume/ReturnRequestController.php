<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeReturnRequest;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class ReturnRequestController extends Controller
{
    use ResponseTrait;

    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of return requests.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $returns = PerfumeReturnRequest::with(['order', 'user'])
                ->when($request->status, fn ($q) => $q->where('status', $request->status))
                ->latest()
                ->paginate(20);

            return response()->json(['html' => view('admin.perfume.returns.table', compact('returns'))->render()]);
        }

        return view('admin.perfume.returns.index');
    }

    /**
     * Display the specified return request.
     */
    public function show($id): View
    {
        $return = PerfumeReturnRequest::with(['order.items', 'user'])->findOrFail($id);

        return view('admin.perfume.returns.show', compact('return'));
    }

    /**
     * Update the status of the return request.
     */
    public function updateStatus(Request $request, $id)
    {
        $return = PerfumeReturnRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,refunded,exchanged',
            'admin_note' => 'nullable|string',
        ]);

        $return->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        $this->activityLog->log('admin.logs.update_perfume_return_status');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated')]);
    }

    /**
     * Remove the specified return request from storage.
     */
    public function destroy($id)
    {
        $return = PerfumeReturnRequest::findOrFail($id);
        $return->delete();

        $this->activityLog->log('admin.logs.delete_perfume_return');

        return Response::json(['id' => $id]);
    }

    /**
     * Bulk delete return requests.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No IDs provided']);

        PerfumeReturnRequest::whereIn('id', $ids)->delete();
        $this->activityLog->log('admin.logs.delete_perfume_return');

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
