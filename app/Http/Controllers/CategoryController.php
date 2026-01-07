<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Menampilkan semua kategori beserta produk di dalamnya
     */
    public function index(): JsonResponse
    {
        // Menggunakan 'with' agar data produk ikut tampil (Relasi)
        $categories = Category::with('products')->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar semua kategori',
            'data'    => $categories
        ], 200);
    }

    /**
     * Menyimpan kategori baru
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 400);
        }

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dibuat',
            'data'    => $category
        ], 201);
    }

    /**
     * Menampilkan detail satu kategori
     */
    public function show($id): JsonResponse
    {
        $category = Category::with('products')->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $category
        ], 200);
    }

    /**
     * Update kategori
     */
    public function update(Request $request, $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 400);
        }

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data'    => $category
        ], 200);
    }

    /**
     * Menghapus kategori
     */
    public function destroy($id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ], 200);
    }
}