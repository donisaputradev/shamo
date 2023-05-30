<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        return view('pages.gallery.index', [
            'title' => 'Gallery',
            'galleries' => ProductGallery::orderBy('created_at', 'desc')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('pages.gallery.create', [
            'title' => 'Add Gallery',
            'products' => Product::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'url' => 'required|image|file|max:2048',
            'product_id' => 'required|exists:products,id',
        ]);

        $validatedData['url'] = $request->file('url')->store('products');

        ProductGallery::create($validatedData);

        return redirect()->route('gallery');
    }

    public function edit(ProductGallery $gallery)
    {
        return view('pages.gallery.edit', [
            'title' => 'Edit Product Gallery',
            'gallery' => $gallery,
            'products' => Product::all(),
        ]);
    }

    public function update(ProductGallery $gallery, Request $request)
    {
        $validatedData = $request->validate([
            'url' => 'image|file|max:2048',
            'product_id' => 'required|exists:products,id',
        ]);

        if ($request->file('url')) {
            if ($request->oldImage) {
                Storage::delete($request->oldImage);
            }
            $validatedData['url'] = $request->file('url')->store('products');
        }

        ProductGallery::where('id', $gallery->id)->update($validatedData);

        return redirect()->route('gallery');
    }

    public function delete(ProductGallery $gallery)
    {
        if ($gallery->url) {
            Storage::delete($gallery->url);
        }
        ProductGallery::destroy($gallery->id);
        return redirect()->route('gallery');
    }
}
