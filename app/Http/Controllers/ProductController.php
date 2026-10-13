<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        // BONUS eager loading: 3 query total (products, categories, tags) berapapun jumlah produk.
        // Tanpa with(): 1 + N + N query (masalah N+1).
        $products = Product::with(['category', 'tags'])
            ->withCount('orderItems')
            ->latest()
            ->paginate(15);

        return view('manage.products.index', compact('products'));
    }
}
