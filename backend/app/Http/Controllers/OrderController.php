<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    // ✅ Création d'une commande
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        return DB::transaction(function () use ($validated, $user) {
            $total = 0;
            $orderItems = [];

            // Vérification du stock
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);

                if ($product->stock < $item['qty']) {
                    throw ValidationException::withMessages([
                        'stock' => ["Stock insuffisant pour le produit {$product->name}"],
                    ]);
                }

                // Calcul du total
                $lineTotal = $product->price * $item['qty'];
                $total += $lineTotal;

                // Préparer la ligne
                $orderItems[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'unit_price' => $product->price,
                ];

                // Décrémenter le stock
                $product->decrement('stock', $item['qty']);
            }

            // Création de la commande
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'PENDING',
            ]);

            // Enregistrer les lignes
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            return response()->json([
                'message' => 'Commande créée avec succès',
                'order' => $order->load('items.product')
            ], 201);
        });
    }

    // ✅ Liste des commandes de l’utilisateur connecté
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders);
    }

    // ✅ Détail d'une commande
    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return response()->json($order);
    }
}
