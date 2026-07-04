<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaterialCode;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class MaterialCodeController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::where('status', 'active')->get();
        $query = MaterialCode::with('category');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('material_name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category_id) {
            $query->where('product_category_id', $request->category_id);
        }

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'material_codes_export.xlsx',
                function ($code) {
                    return [
                        'Code' => $code->code,
                        'Category' => $code->category->name ?? 'N/A',
                        'Sub Category' => $code->sub_category,
                        'Sub Category Two' => $code->sub_category_two,
                        'Brand' => $code->brand,
                        'Material Name' => $code->material_name,
                        'Created At' => $code->created_at->format('Y-m-d H:i:s'),
                    ];
                }
            );
        }

        $materialCodes = $query->latest()->paginate(10)->withQueryString();
        return view('admin.purchase.material-code', compact('categories', 'materialCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'sub_category' => 'required|string|max:255',
            'sub_category_two' => 'nullable|string|max:255',
            'brand' => 'required|string|max:255',
            'material_name' => 'required|string|max:255',
        ]);

        MaterialCode::create($request->all());

        return redirect()->back()->with('success', 'Material Code created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'sub_category' => 'required|string|max:255',
            'sub_category_two' => 'nullable|string|max:255',
            'brand' => 'required|string|max:255',
            'material_name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:material_codes,code,' . $id,
        ]);

        $materialCode = MaterialCode::findOrFail($id);
        $materialCode->update($request->all());

        return redirect()->back()->with('success', 'Material Code updated successfully.');
    }

    public function destroy($id)
    {
        $materialCode = MaterialCode::findOrFail($id);
        $materialCode->delete();

        return redirect()->back()->with('success', 'Material Code deleted successfully.');
    }
}
