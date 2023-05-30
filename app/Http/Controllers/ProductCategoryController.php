<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return view('pages.product_category.index', [
            'title' => 'Product Category',
            'categories' => ProductCategory::orderBy('created_at', 'desc')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('pages.product_category.create', ['title' => 'Create Product Category']);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
        ]);

        ProductCategory::create($validatedData);

        return redirect()->route('category');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('pages.product_category.edit', [
            'title' => 'Edit Product Category',
            'category' => $productCategory,
        ]);
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validatedData = $request->validate([
            'name' => 'required|String|max:255',
        ]);

        ProductCategory::where('id', $productCategory->id)->update($validatedData);

        return redirect()->route('category');
    }

    public function delete(ProductCategory $productCategory)
    {
        ProductCategory::destroy($productCategory->id);
        return redirect()->route('category');
    }
}
