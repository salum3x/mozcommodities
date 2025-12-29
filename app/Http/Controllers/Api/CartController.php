<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Get cart items
     */
    public function index(Request $request)
    {
        $cartItems = CartItem::with(['product.category', 'product.supplier'])
            ->where('user_id', $request->user()->id)
            ->get();

        $items = $cartItems->map(function ($item) {
            return $this->formatCartItem($item);
        });

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price_per_kg;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'items' => $items,
                'items_count' => $cartItems->count(),
                'subtotal' => (float) $subtotal,
                'subtotal_formatted' => number_format($subtotal, 2, ',', '.') . ' MZN',
            ]
        ]);
    }

    /**
     * Add item to cart
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:0.1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::where('id', $request->product_id)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado ou indisponível'
            ], 404);
        }

        // Check minimum quantity
        if ($request->quantity < $product->min_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Quantidade mínima é {$product->min_quantity} {$product->unit}"
            ], 422);
        }

        // Check stock
        if ($request->quantity > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Stock insuficiente. Disponível: {$product->stock_quantity} {$product->unit}"
            ], 422);
        }

        // Check if item already in cart
        $existingItem = CartItem::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $request->quantity;

            if ($newQuantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente. Disponível: {$product->stock_quantity} {$product->unit}"
                ], 422);
            }

            $existingItem->update([
                'quantity' => $newQuantity,
                'price_per_kg' => $product->price_per_kg,
            ]);

            $cartItem = $existingItem;
        } else {
            $cartItem = CartItem::create([
                'user_id' => $request->user()->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price_per_kg' => $product->price_per_kg,
            ]);
        }

        $cartItem->load('product.category');

        return response()->json([
            'success' => true,
            'message' => 'Produto adicionado ao carrinho',
            'data' => $this->formatCartItem($cartItem)
        ], 201);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => ['required', 'numeric', 'min:0.1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item não encontrado no carrinho'
            ], 404);
        }

        $product = $cartItem->product;

        // Check minimum quantity
        if ($request->quantity < $product->min_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Quantidade mínima é {$product->min_quantity} {$product->unit}"
            ], 422);
        }

        // Check stock
        if ($request->quantity > $product->stock_quantity) {
            return response()->json([
                'success' => false,
                'message' => "Stock insuficiente. Disponível: {$product->stock_quantity} {$product->unit}"
            ], 422);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'price_per_kg' => $product->price_per_kg,
        ]);

        $cartItem->load('product.category');

        return response()->json([
            'success' => true,
            'message' => 'Quantidade atualizada',
            'data' => $this->formatCartItem($cartItem)
        ]);
    }

    /**
     * Remove item from cart
     */
    public function destroy(Request $request, $id)
    {
        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item não encontrado no carrinho'
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item removido do carrinho'
        ]);
    }

    /**
     * Clear cart
     */
    public function clear(Request $request)
    {
        CartItem::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Carrinho limpo'
        ]);
    }

    /**
     * Format cart item for response
     */
    protected function formatCartItem(CartItem $item): array
    {
        return [
            'id' => $item->id,
            'quantity' => (float) $item->quantity,
            'price_per_kg' => (float) $item->price_per_kg,
            'subtotal' => (float) ($item->quantity * $item->price_per_kg),
            'subtotal_formatted' => number_format($item->quantity * $item->price_per_kg, 2, ',', '.') . ' MZN',
            'product' => [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'slug' => $item->product->slug,
                'image' => $item->product->image ? asset('storage/' . $item->product->image) : null,
                'unit' => $item->product->unit ?? 'kg',
                'min_quantity' => (float) $item->product->min_quantity,
                'stock_quantity' => (float) $item->product->stock_quantity,
                'category' => $item->product->category ? [
                    'id' => $item->product->category->id,
                    'name' => $item->product->category->name,
                ] : null,
            ],
        ];
    }
}
