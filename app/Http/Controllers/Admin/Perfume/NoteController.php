<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeEssentialOil;
use App\Models\PerfumeNote;
use App\Models\PerfumeOilCategory;
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class NoteController extends Controller
{
    use ResponseTrait;

    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of notes.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $notes = PerfumeNote::with(['essentialOil', 'oilCategory'])->orderBy('priority')->paginate(20);
            return response()->json(['html' => view('admin.perfume.notes.table', compact('notes'))->render()]);
        }

        return view('admin.perfume.notes.index');
    }

    /**
     * Show the form for creating a new note.
     */
    public function create(): View
    {
        $oils = PerfumeEssentialOil::where('is_active', true)->orderBy('priority')->get();
        $categories = PerfumeOilCategory::where('is_active', true)->orderBy('priority')->get();

        return view('admin.perfume.notes.create', compact('oils', 'categories'));
    }

    /**
     * Store a newly created note in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'essential_oil_id' => 'nullable|exists:perfume_essential_oils,id',
            'oil_category_id' => 'nullable|exists:perfume_oil_categories,id',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Auto-assign category from essential oil if missing
        if (!empty($validated['essential_oil_id']) && empty($validated['oil_category_id'])) {
            $validated['oil_category_id'] = PerfumeEssentialOil::where('id', $validated['essential_oil_id'])->value('oil_category_id');
        }

        $note = new PerfumeNote();

        if ($request->hasFile('image')) {
            $validated['image'] = $note->uploadFile($request->file('image'), 'perfume/notes');
        }

        PerfumeNote::create($validated);

        $this->activityLog->log('admin.logs.create_perfume_note');

        return Response::json(['status' => 'success', 'msg' => __('admin.created'), 'url' => route('admin.perfume.notes.index')]);
    }

    /**
     * Show the form for editing the specified note.
     */
    public function edit($id): View
    {
        $note = PerfumeNote::findOrFail($id);
        $oils = PerfumeEssentialOil::where('is_active', true)->orderBy('priority')->get();
        $categories = PerfumeOilCategory::where('is_active', true)->orderBy('priority')->get();

        return view('admin.perfume.notes.edit', compact('note', 'oils', 'categories'));
    }

    /**
     * Update the specified note in storage.
     */
    public function update(Request $request, $id)
    {
        $note = PerfumeNote::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'essential_oil_id' => 'nullable|exists:perfume_essential_oils,id',
            'oil_category_id' => 'nullable|exists:perfume_oil_categories,id',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if (!empty($validated['essential_oil_id']) && empty($validated['oil_category_id'])) {
            $validated['oil_category_id'] = PerfumeEssentialOil::where('id', $validated['essential_oil_id'])->value('oil_category_id');
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $note->uploadFile($request->file('image'), 'perfume/notes');
        }

        $note->update($validated);

        $this->activityLog->log('admin.logs.update_perfume_note');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated'), 'url' => route('admin.perfume.notes.index')]);
    }

    /**
     * Display the specified note.
     */
    public function show($id): View
    {
        $note = PerfumeNote::with(['essentialOil', 'oilCategory'])->findOrFail($id);
        return view('admin.perfume.notes.show', compact('note'));
    }

    /**
     * Remove the specified note from storage.
     */
    public function destroy($id)
    {
        $note = PerfumeNote::findOrFail($id);
        $note->delete();

        $this->activityLog->log('admin.logs.delete_perfume_note');

        return Response::json(['id' => $id]);
    }

    /**
     * Toggle note active status.
     */
    public function toggle($id)
    {
        $note = PerfumeNote::findOrFail($id);
        $note->update(['is_active' => !$note->is_active]);
        
        $this->activityLog->log('admin.logs.update_perfume_note');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated')]);
    }

    /**
     * Bulk delete notes.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No IDs provided']);

        PerfumeNote::whereIn('id', $ids)->delete();
        $this->activityLog->log('admin.logs.delete_perfume_note');

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
