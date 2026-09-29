<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('status', 'active');

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::orderBy('name')->get();

        $recommendedProducts = collect();
        if (auth()->check()) {
            $recommendationService = app(RecommendationService::class);
            $recommendedProducts = $recommendationService->getRecommendationsForUser(auth()->id(), 5);
        }

        return view('customer.catalog', compact('products', 'categories', 'recommendedProducts'));
    }

    public function show(string $id)
    {
        $product = Product::with(['category', 'reviews.user'])->where('status', 'active')->findOrFail($id);

        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('customer.product_detail', compact('product', 'similarProducts'));
    }
}
