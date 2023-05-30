<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.product.index', [
            'title' => 'Product',
            'products' => Product::orderBy('created_at', 'desc')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('pages.product.create', [
            'title' => 'Create Product',
            'categories' => ProductCategory::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'tags' => 'required|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
        ]);

        Product::create($validatedData);

        return redirect()->route('product');
    }

    public function edit(Product $product)
    {
        return view('pages.product.edit', [
            'title' => 'Product',
            'categories' => ProductCategory::all(),
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'tags' => 'required|max:255',
            'product_category_id' => 'required',
        ]);

        Product::where('id', $product->id)->update($validatedData);

        return redirect()->route('product');
    }

    public function delete(Product $product)
    {
        foreach ($product->galleries as $gallery) {
            Storage::delete($gallery->url);
            ProductGallery::destroy($gallery->id);
        }
        Product::destroy($product->id);
        return redirect()->route('product');
    }
}
