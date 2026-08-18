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

        if ($request->has('export') && $request->export === 'excel') {
            return \App\Services\ExportService::exportToExcel(
                $query->latest(),
                'product_categories_export.xlsx',
                function ($category) {
                    return [
                        'Name' => $category->name,
                        'Status' => ucfirst($category->status),
                        'Created At' => $category->created_at->format('Y-m-d H:i:s'),
                    ];
                }
            );
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

        $data = $request->all();
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['creator_type'] = 'coordinator';
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['created_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['creator_type'] = 'admin';
        }
        ProductCategory::create($data);

        return redirect()->back()->with('success', 'Product Category created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $id,
            'status' => 'required|in:active,inactive',
        ]);

        $category = ProductCategory::findOrFail($id);
        $data = $request->all();
        if (\Illuminate\Support\Facades\Auth::guard('web')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('web')->id();
            $data['updater_type'] = 'coordinator';
        } else if (\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $data['updated_by'] = \Illuminate\Support\Facades\Auth::guard('admin')->id();
            $data['updater_type'] = 'admin';
        }
        $category->update($data);

        return redirect()->back()->with('success', 'Product Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Product Category deleted successfully.');
    }
}
