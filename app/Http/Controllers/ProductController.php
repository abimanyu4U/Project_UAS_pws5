<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Menampilkan semua produk beserta kategorinya
     */
    public function index(): JsonResponse
    {
        $products = Product::with('category')->get();
        return response()->json([
            'success' => true,
            'data'    => $products
        ], 200);
    }

    /**
     * Menyimpan produk baru
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|integer',
            'stock'       => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $product = Product::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data'    => $product
        ], 201);
    }

    /**
     * Menampilkan detail satu produk
     */
    public function show($id): JsonResponse
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $product
        ], 200);
    }

    /**
     * Update produk
     */
    public function update(Request $request, $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data'    => $product
        ], 200);
    }

    /**
     * Menghapus produk
     */
    public function destroy($id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ], 200);
    }
}