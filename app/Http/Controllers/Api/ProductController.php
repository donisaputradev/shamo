<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseFormatter;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        try {
            $id = $request->input('id');
            $limit = $request->input('limit', 10);
            $search = $request->input('search');
            $tags = $request->input('tags');
            $categoryId = $request->input('category_id');

            $priceFrom = $request->input('price_from');
            $priceStart = $request->input('price_start');

            if ($id) {
                $product = Product::with(['category', 'galleries'])->find($id);

                if ($product) {
                    return ResponseFormatter::success($product, 'Product data retrieved successfully!');
                } else {
                    return ResponseFormatter::error(null, 'Product data does not exist!', 404);
                }
            }

            $products = Product::with(['category', 'galleries']);

            if ($search) {
                $products->where('name', 'like', '%' . $search . '%');
            }

            if ($tags) {

                $products->where('tags', 'like', '%' . $tags . '%');
            }

            if ($priceFrom) {
                $products->where('price', '>=', $priceFrom);
            }

            if ($priceStart) {
                $products->where('price', '<=', $priceStart);
            }

            if ($categoryId) {
                $products->where('product_category_id', $categoryId);
            }

            return ResponseFormatter::success($products->paginate($limit), 'Product data retrieved successfully!');
        } catch (Exception $error) {
            return ResponseFormatter::error(null, $error->getMessage(), 500);
        }
    }
}
