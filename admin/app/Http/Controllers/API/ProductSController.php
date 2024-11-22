<?php
namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductSController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            // Debug the category_id value
            \Log::info('Category ID received:', ['category_id' => $request->category_id]);

            // Filter by category_id if provided
            if ($request->has('category_id')) {
                $categoryId = $request->category_id;
                $query->where('category_id', $categoryId);

                // Check if category exists
                $category = Category::find($categoryId);
                if (!$category) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Category not found',
                    ], 404);
                }
            }

            // Add with() to eager load the category relationship
            $query->with('category');

            // Get the products
            $products = $query->paginate(10);

            // Debug the query
            \Log::info('SQL Query:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            // Debug the results count
            \Log::info('Products found:', ['count' => $products->count()]);

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'products' => $products,
                        'total' => $products->total(),
                        'per_page' => $products->perPage(),
                        'current_page' => $products->currentPage(),
                        'last_page' => $products->lastPage(),
                        'category' => $request->has('category_id') ? $category : null,
                    ],
                ]);
            }

            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            \Log::error('Error in products index:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching products',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $product = Product::with('category')->find($id);

            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product not found',
                ], 404);
            }

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'status' => 'success',
                    'data' => $product,
                ]);
            }

            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching the product',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'productname' => 'required',
                'description' => 'required',
                'category_id' => 'required|exists:categories,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'annual_prices' => 'required|array',
                'annual_prices.single_site' => 'required|numeric',
                'annual_prices.up_to_5_sites' => 'required|numeric',
                'annual_prices.up_to_20_sites' => 'required|numeric',
                'lifetime_prices' => 'required|array',
                'lifetime_prices.single_site' => 'required|numeric',
                'lifetime_prices.up_to_5_sites' => 'required|numeric',
                'lifetime_prices.up_to_20_sites' => 'required|numeric',
            ]);

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            $product = Product::create([
                'productname' => $request->productname,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'image' => $imageName,
                'price_structure' => 'tiered',
                'legacy_price' => null,
                'price_tiers' => [
                    'annual' => [
                        'single_site' => $request->annual_prices['single_site'],
                        'up_to_5_sites' => $request->annual_prices['up_to_5_sites'],
                        'up_to_20_sites' => $request->annual_prices['up_to_20_sites'],
                    ],
                    'lifetime' => [
                        'single_site' => $request->lifetime_prices['single_site'],
                        'up_to_5_sites' => $request->lifetime_prices['up_to_5_sites'],
                        'up_to_20_sites' => $request->lifetime_prices['up_to_20_sites'],
                    ],
                ],
            ]);

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product created successfully',
                    'data' => $product->load('category'),
                ], 201);
            }

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the product',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validatedData = $request->validate([
                'productname' => 'required',
                'description' => 'required',
                'category_id' => 'required|exists:categories,id',
                'annual_prices' => 'required|array',
                'annual_prices.single_site' => 'required|numeric',
                'annual_prices.up_to_5_sites' => 'required|numeric',
                'annual_prices.up_to_20_sites' => 'required|numeric',
                'lifetime_prices' => 'required|array',
                'lifetime_prices.single_site' => 'required|numeric',
                'lifetime_prices.up_to_5_sites' => 'required|numeric',
                'lifetime_prices.up_to_20_sites' => 'required|numeric',
            ]);

            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('images'), $imageName);
                $product->image = $imageName;
            }

            $product->update([
                'productname' => $request->productname,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'price_structure' => 'tiered',
                'legacy_price' => null,
                'price_tiers' => [
                    'annual' => [
                        'single_site' => $request->annual_prices['single_site'],
                        'up_to_5_sites' => $request->annual_prices['up_to_5_sites'],
                        'up_to_20_sites' => $request->annual_prices['up_to_20_sites'],
                    ],
                    'lifetime' => [
                        'single_site' => $request->lifetime_prices['single_site'],
                        'up_to_5_sites' => $request->lifetime_prices['up_to_5_sites'],
                        'up_to_20_sites' => $request->lifetime_prices['up_to_20_sites'],
                    ],
                ],
            ]);

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Product updated successfully',
                    'data' => $product->load('category'),
                ]);
            }

            return redirect()->route('products.index')->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the product',
                'debug_message' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function isApiRequest(Request $request)
    {
        return $request->is('api/*') || $request->expectsJson();
    }
}
