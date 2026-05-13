<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductCategory::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(10)->withQueryString();
        return view('admin.purchase.product-category', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
            'status' => 'required|in:active,inactive',
        ]);

        ProductCategory::create($request->all());

        return redirect()->back()->with('success', 'Product Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $category = ProductCategory::findOrFail($id);
        $category->update($request->all());

        return redirect()->back()->with('success', 'Product Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Product Category deleted successfully.');
    }
}
