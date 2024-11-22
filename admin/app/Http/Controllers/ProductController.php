<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'productname' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'source_file' => 'required|file|mimes:zip,rar,7z|max:10240', // 10MB max
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

        // Handle image upload
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        // Handle source file upload
        $sourceFileName = time() . '_source.' . $request->source_file->extension();
        $request->source_file->move(public_path('source_files'), $sourceFileName);

        $product = Product::create([
            'productname' => $request->productname,
            'description' => $request->description,
            'image' => $imageName,
            'source_file' => $sourceFileName,
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
                'message' => 'Product created successfully',
                'data' => $product,
            ], 201);
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'productname' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'source_file' => 'nullable|file|mimes:zip,rar,7z|max:10240', // 10MB max
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

        // Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            // Delete old image
            if (file_exists(public_path('images/' . $product->image))) {
                unlink(public_path('images/' . $product->image));
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        // Handle source file upload if new file is provided
        if ($request->hasFile('source_file')) {
            // Delete old source file
            if (file_exists(public_path('source_files/' . $product->source_file))) {
                unlink(public_path('source_files/' . $product->source_file));
            }

            $sourceFileName = time() . '_source.' . $request->source_file->extension();
            $request->source_file->move(public_path('source_files'), $sourceFileName);
            $product->source_file = $sourceFileName;
        }

        $product->update([
            'productname' => $request->productname,
            'description' => $request->description,
            'image' => $product->image,
            'source_file' => $product->source_file,
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
                'data' => $product,
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete the product's image and source file from the public directory
        if (file_exists(public_path('images/' . $product->image))) {
            unlink(public_path('images/' . $product->image));
        }

        if (file_exists(public_path('source_files/' . $product->source_file))) {
            unlink(public_path('source_files/' . $product->source_file));
        }

        $product->delete();

        if ($this->isApiRequest(request())) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully',
            ], 200);
        }

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    private function isApiRequest(Request $request)
    {
        return $request->is('api/*') || $request->expectsJson();
    }
}
