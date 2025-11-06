<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ✅ Liste paginée + filtres
    public function index(Request $request)
    {
        $query = Product::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'LIKE', "%$search%")
                  ->orWhere('sku', 'LIKE', "%$search%");
        }

        $products = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json($products);
    }

    // ✅ Création
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return response()->json(['message' => 'Produit créé avec succès', 'product' => $product], 201);
    }

    // ✅ Détail
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    // ✅ Mise à jour
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        return response()->json(['message' => 'Produit mis à jour avec succès', 'product' => $product]);
    }

    // ✅ Suppression
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Produit supprimé avec succès']);
    }
}
