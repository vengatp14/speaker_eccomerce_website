<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories')); // Return index view with categories
    }

    public function category()
    {
        $categories = Category::all();
        return response()->json($categories);// Return index view with categories
    }
    public function create()
    {
        return view('categories.create'); // Return create view
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        $imagePath = $request->file('image')->store('images', 'public');

        // Create a new category
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!'); // Redirect back to index with success message
    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category')); // Return show view with category
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category')); // Return edit view with category
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload if an image is provided
        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image); // Remove the old image
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $category->image = $imagePath; // Update the image path
        }

        // Update other fields
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!'); // Redirect back to index with success message
    }

    public function destroy(Category $category)
    {
        // Delete the category
        if ($category->image) {
            Storage::disk('public')->delete($category->image); // Remove the image from storage
        }
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!'); // Redirect back to index with success message
    }
}
