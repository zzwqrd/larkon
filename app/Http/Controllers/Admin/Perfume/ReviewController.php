<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeReview;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class ReviewController extends Controller
{
    use ResponseTrait;

    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $reviews = PerfumeReview::with(['product', 'user'])
                ->when($request->status, fn ($q) => $q->where('status', $request->status))
                ->latest()
                ->paginate(20);

            return response()->json(['html' => view('admin.perfume.reviews.table', compact('reviews'))->render()]);
        }

        return view('admin.perfume.reviews.index');
    }

    /**
     * Display the specified review.
     */
    public function show($id): View
    {
        $review = PerfumeReview::with(['product', 'user', 'order'])->findOrFail($id);

        return view('admin.perfume.reviews.show', compact('review'));
    }

    /**
     * Approve the specified review.
     */
    public function approve($id)
    {
        $review = PerfumeReview::findOrFail($id);
        $review->update(['status' => 'approved']);

        $this->activityLog->log('admin.logs.approve_perfume_review');

        return Response::json([
            'status' => 'success',
            'msg' => __('admin.updated'),
            'url' => route('admin.perfume.reviews.index'),
        ]);
    }

    /**
     * Reject the specified review.
     */
    public function reject($id)
    {
        $review = PerfumeReview::findOrFail($id);
        $review->update(['status' => 'rejected']);

        $this->activityLog->log('admin.logs.reject_perfume_review');

        return Response::json([
            'status' => 'success',
            'msg' => __('admin.updated'),
            'url' => route('admin.perfume.reviews.index'),
        ]);
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy($id)
    {
        $review = PerfumeReview::findOrFail($id);
        $review->delete();

        $this->activityLog->log('admin.logs.delete_perfume_review');

        return Response::json(['id' => $id]);
    }

    /**
     * Bulk delete reviews.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No IDs provided']);

        PerfumeReview::whereIn('id', $ids)->delete();
        $this->activityLog->log('admin.logs.delete_perfume_review');

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
