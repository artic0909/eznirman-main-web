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

        $materialCodes = $query->latest()->paginate(10)->withQueryString();
        return view('admin.purchase.material-code', compact('categories', 'materialCodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:material_codes,code',
            'product_category_id' => 'required|exists:product_categories,id',
            'material_name' => 'required|string|max:255',
        ]);

        MaterialCode::create($request->all());

        return redirect()->back()->with('success', 'Material Code created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:material_codes,code,' . $id,
            'product_category_id' => 'required|exists:product_categories,id',
            'material_name' => 'required|string|max:255',
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
