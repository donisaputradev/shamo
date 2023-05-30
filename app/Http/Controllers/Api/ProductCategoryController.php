<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseFormatter;
use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function all(Request $request)
    {
        try {
            $id = $request->input('id');
            $limit = $request->input('limit', 10);
            $search = $request->input('search');
            $isProduct = $request->input('is_products');

            if ($id) {
                $products = Product::where('product_category_id', $id)->get();
                if ($products) {
                    return ResponseFormatter::success($products, 'Category data retrieved successfully!');
                } else {
                    return ResponseFormatter::error(null, 'Category data does not exist!', 404);
                }
            }

            $categories = ProductCategory::query();

            if ($search) {
                $categories->where('name', 'like', '%' . $search . '%');
            }

            if ($isProduct == true) {
                $categories->with('products');
            }

            return ResponseFormatter::success($categories->paginate($limit), 'Category data retrieved successfully!');
        } catch (Exception $error) {
            return ResponseFormatter::error(null, $error->getMessage(), 500);
        }
    }
}
