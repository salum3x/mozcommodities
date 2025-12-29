<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all products with filters
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier.user'])
            ->where('is_active', true)
            ->where('is_approved', true);

        // Filter by category
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by category slug
        if ($request->has('category_slug')) {
            $category = Category::where('slug', $request->category_slug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filter by supplier type (our products vs supplier products)
        if ($request->has('type')) {
            if ($request->type === 'our') {
                $query->whereNull('supplier_id');
            } elseif ($request->type === 'supplier') {
                $query->whereNotNull('supplier_id');
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Price range
        if ($request->has('min_price')) {
            $query->where('price_per_kg', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price_per_kg', '<=', $request->max_price);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        if ($sortBy === 'price') {
            $query->orderBy('price_per_kg', $sortOrder);
        } elseif ($sortBy === 'name') {
            $query->orderBy('name', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $products = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products->items() ? collect($products->items())->map(function ($product) {
                return $this->formatProduct($product);
            }) : [],
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ]
        ]);
    }

    /**
     * Get featured/highlighted products
     */
    public function featured()
    {
        $products = Product::with(['category', 'supplier.user'])
            ->where('is_active', true)
            ->where('is_approved', true)
            ->where('is_featured', true)
            ->take(10)
            ->get()
            ->map(function ($product) {
                return $this->formatProduct($product);
            });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get latest products
     */
    public function latest()
    {
        $products = Product::with(['category', 'supplier.user'])
            ->where('is_active', true)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($product) {
                return $this->formatProduct($product);
            });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Get single product
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'supplier.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado'
            ], 404);
        }

        // Get related products
        $related = Product::with(['category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('is_approved', true)
            ->take(6)
            ->get()
            ->map(function ($p) {
                return $this->formatProduct($p, false);
            });

        return response()->json([
            'success' => true,
            'data' => $this->formatProduct($product, true),
            'related' => $related
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');

        if (strlen($search) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Termo de pesquisa muito curto'
            ], 422);
        }

        $products = Product::with(['category'])
            ->where('is_active', true)
            ->where('is_approved', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->take(20)
            ->get()
            ->map(function ($product) {
                return $this->formatProduct($product, false);
            });

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Format product data for response
     */
    protected function formatProduct(Product $product, bool $full = false): array
    {
        $data = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price_per_kg' => (float) $product->price_per_kg,
            'price_formatted' => number_format($product->price_per_kg, 2, ',', '.') . ' MZN/kg',
            'min_quantity' => (float) $product->min_quantity,
            'stock_quantity' => (float) $product->stock_quantity,
            'unit' => $product->unit ?? 'kg',
            'image' => $product->image ? asset('storage/' . $product->image) : null,
            'is_featured' => $product->is_featured,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ] : null,
            'supplier' => $product->supplier ? [
                'id' => $product->supplier->id,
                'company_name' => $product->supplier->company_name,
                'is_verified' => $product->supplier->is_verified,
            ] : null,
            'is_own_product' => $product->supplier_id === null,
        ];

        if ($full) {
            $data['description'] = $product->description;
            $data['origin'] = $product->origin;
            $data['harvest_date'] = $product->harvest_date;
            $data['certifications'] = $product->certifications;
            $data['created_at'] = $product->created_at->toISOString();
        }

        return $data;
    }
}
