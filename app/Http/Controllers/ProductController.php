<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Ensure you have the Product model

class ProductController extends Controller
{
    // GET /api/products → Returns all products
    public function index()
    {
        return response()->json(Product::all(), 200);
    }

    // POST /api/products → Creates a new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }

    // PUT /api/products/{id} → Updates an existing product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric',
            'description' => 'nullable|string',
        ]);

        $product->update($request->all());

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200);
    }
    public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();

    return response()->json(['message' => 'Product deleted successfully'], 200);
}

}
