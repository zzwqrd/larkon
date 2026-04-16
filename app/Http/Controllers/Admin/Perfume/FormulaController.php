<?php

namespace App\Http\Controllers\Admin\Perfume;

use App\Http\Controllers\Controller;
use App\Models\PerfumeEssentialOil;
use App\Models\PerfumeFormula;
use App\Models\PerfumeFormulaIngredient;
use App\Models\Product; // Using Larkon's Product model
use App\Services\ActivityLogService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class FormulaController extends Controller
{
    use ResponseTrait;

    protected $activityLog;

    public function __construct(ActivityLogService $activityLog)
    {
        $this->activityLog = $activityLog;
    }

    /**
     * Display a listing of formulas.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $formulas = PerfumeFormula::with('product')->orderBy('priority')->paginate(20);
            return response()->json(['html' => view('admin.perfume.formulas.table', compact('formulas'))->render()]);
        }

        return view('admin.perfume.formulas.index');
    }

    /**
     * Show the form for creating a new formula.
     */
    public function create(): View
    {
        $products = Product::orderBy('name')->get(); // Using standard product listing
        $oils = PerfumeEssentialOil::where('is_active', true)->orderBy('name')->get();

        return view('admin.perfume.formulas.create', compact('products', 'oils'));
    }

    /**
     * Store a newly created formula and its ingredients in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,webm|max:10240', // 10MB approx max
            'product_id' => 'nullable|exists:products,id',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $formula = new PerfumeFormula();

        if ($request->hasFile('image')) {
            $validated['image'] = $formula->uploadFile($request->file('image'), 'perfume/formulas');
        }
        if ($request->hasFile('video')) {
            $validated['video'] = $formula->uploadFile($request->file('video'), 'perfume/formulas');
        }

        $created = PerfumeFormula::create($validated);

        // Save ingredients
        if ($request->has('ingredients') && is_array($request->ingredients)) {
            foreach ($request->ingredients as $ingredient) {
                if (!empty($ingredient['ingredient_name']) || !empty($ingredient['essential_oil_id'])) {
                    $ing = new PerfumeFormulaIngredient();
                    $ingData = [
                        'formula_id' => $created->id,
                        'essential_oil_id' => $ingredient['essential_oil_id'] ?? null,
                        'ingredient_name' => $ingredient['ingredient_name'] ?? null,
                        'percentage' => $ingredient['percentage'] ?? null,
                        'note_type' => $ingredient['note_type'] ?? null,
                        'note' => $ingredient['note'] ?? null,
                        'priority' => $ingredient['priority'] ?? 0,
                    ];
                    if (!empty($ingredient['image_file'])) {
                        $ingData['image'] = $ing->uploadFile($ingredient['image_file'], 'perfume/ingredients');
                    }
                    PerfumeFormulaIngredient::create($ingData);
                }
            }
        }

        $this->activityLog->log('admin.logs.create_perfume_formula');

        return Response::json(['status' => 'success', 'msg' => __('admin.created'), 'url' => route('admin.perfume.formulas.index')]);
    }

    /**
     * Show the form for editing the specified formula.
     */
    public function edit($id): View
    {
        $formula = PerfumeFormula::with('ingredients.essentialOil')->findOrFail($id);
        $products = Product::orderBy('name')->get();
        $oils = PerfumeEssentialOil::where('is_active', true)->orderBy('name')->get();

        return view('admin.perfume.formulas.edit', compact('formula', 'products', 'oils'));
    }

    /**
     * Update the specified formula and its ingredients in storage.
     */
    public function update(Request $request, $id)
    {
        $formula = PerfumeFormula::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimes:mp4,webm|max:10240',
            'product_id' => 'nullable|exists:products,id',
            'priority' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $formula->uploadFile($request->file('image'), 'perfume/formulas');
        }
        if ($request->hasFile('video')) {
            $validated['video'] = $formula->uploadFile($request->file('video'), 'perfume/formulas');
        }

        $formula->update($validated);

        // Update ingredients (simplified logic: delete existing and recreate, or update based on IDs)
        // Here we'll delete the old ones and recreate. For a full robust app, sync by ID is preferred.
        // Assuming the UI sends the full list of ingredients every time.
        if ($request->has('ingredients')) {
             $formula->ingredients()->delete(); // Clear exising

             if (is_array($request->ingredients)) {
                 foreach ($request->ingredients as $ingredient) {
                     if (!empty($ingredient['ingredient_name']) || !empty($ingredient['essential_oil_id'])) {
                         $ing = new PerfumeFormulaIngredient();
                         $ingData = [
                             'formula_id' => $formula->id,
                             'essential_oil_id' => $ingredient['essential_oil_id'] ?? null,
                             'ingredient_name' => $ingredient['ingredient_name'] ?? null,
                             'percentage' => $ingredient['percentage'] ?? null,
                             'note_type' => $ingredient['note_type'] ?? null,
                             'note' => $ingredient['note'] ?? null,
                             'priority' => $ingredient['priority'] ?? 0,
                         ];
                         // if there is an image_file sent in array
                         if (!empty($ingredient['image_file'])) {
                             $ingData['image'] = $ing->uploadFile($ingredient['image_file'], 'perfume/ingredients');
                         }
                         PerfumeFormulaIngredient::create($ingData);
                     }
                 }
             }
        }

        $this->activityLog->log('admin.logs.update_perfume_formula');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated'), 'url' => route('admin.perfume.formulas.index')]);
    }

    /**
     * Display the specified formula.
     */
    public function show($id): View
    {
        $formula = PerfumeFormula::with(['ingredients.essentialOil', 'product'])->findOrFail($id);
        return view('admin.perfume.formulas.show', compact('formula'));
    }

    /**
     * Remove the specified formula from storage.
     */
    public function destroy($id)
    {
        $formula = PerfumeFormula::findOrFail($id);
        $formula->delete(); // Cascades to ingredients

        $this->activityLog->log('admin.logs.delete_perfume_formula');

        return Response::json(['id' => $id]);
    }

    /**
     * Toggle formula active status.
     */
    public function toggle($id)
    {
        $formula = PerfumeFormula::findOrFail($id);
        $formula->update(['is_active' => !$formula->is_active]);
        
        $this->activityLog->log('admin.logs.update_perfume_formula');

        return Response::json(['status' => 'success', 'msg' => __('admin.updated')]);
    }

    /**
     * Bulk delete formulas.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) return response()->json(['success' => false, 'message' => 'No IDs provided']);

        PerfumeFormula::whereIn('id', $ids)->delete();
        $this->activityLog->log('admin.logs.delete_perfume_formula');

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }
}
