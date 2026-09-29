<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    /**
     * Get product recommendations for a specific user.
     * Uses a simplified Item-Based Collaborative Filtering approach based on Wishlists and Orders.
     *
     * @return Collection
     */
    public function getRecommendationsForUser(int $userId, int $limit = 5)
    {
        $productIds = Cache::remember('user_recommendations_ids_'.$userId, now()->addHours(6), function () use ($userId, $limit) {
            // 1. Get items the user has interacted with (Wishlist + Orders)
            $userWishlistProductIds = Wishlist::where('user_id', $userId)->pluck('product_id')->toArray();

            $userOrderedProductIds = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.user_id', $userId)
                ->pluck('order_items.product_id')
                ->toArray();

            $interactedProductIds = array_unique(array_merge($userWishlistProductIds, $userOrderedProductIds));

            if (empty($interactedProductIds)) {
                return $this->getTopSellingProductIds($limit);
            }

            // 2. Find other users who also interacted with these products
            $similarUsers = $this->getSimilarUsers($userId, $interactedProductIds);

            if (empty($similarUsers)) {
                return $this->getTopSellingProductIds($limit);
            }

            // 3. Get products that those similar users interacted with, but the current user hasn't
            $recommendedProductIds = $this->getProductsFromSimilarUsers($similarUsers, $interactedProductIds, $limit);

            if (empty($recommendedProductIds)) {
                return $this->getTopSellingProductIds($limit);
            }

            return $recommendedProductIds;
        });

        if (empty($productIds)) {
            return collect();
        }

        // Lookup produk terbaru agar rekomendasi selalu menampilkan harga & ketersediaan terkini
        $validProducts = Product::whereIn('id', $productIds)
            ->where('status', 'active')
            ->where('stock', '>', 0)
            ->with('category')
            ->get()
            ->keyBy('id');

        // Pertahankan urutan skor rekomendasi (paling relevan lebih dulu)
        return collect($productIds)
            ->map(fn ($id) => $validProducts->get($id))
            ->filter()
            ->take($limit)
            ->values();
    }

    private function getSimilarUsers(int $userId, array $interactedProductIds)
    {
        // Find users who have wishlisted the same products
        $wishlistUsers = DB::table('wishlists')
            ->whereIn('product_id', $interactedProductIds)
            ->where('user_id', '!=', $userId)
            ->pluck('user_id')
            ->toArray();

        // Find users who have ordered the same products
        $orderUsers = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('order_items.product_id', $interactedProductIds)
            ->where('orders.user_id', '!=', $userId)
            ->pluck('orders.user_id')
            ->toArray();

        // Count frequencies to find the most similar users
        $allSimilarUsers = array_merge($wishlistUsers, $orderUsers);
        $userCounts = array_count_values($allSimilarUsers);

        // Sort by users who have the most interactions in common
        arsort($userCounts);

        // Return top 20 most similar users
        return array_slice(array_keys($userCounts), 0, 20);
    }

    private function getProductsFromSimilarUsers(array $similarUsers, array $excludeProductIds, int $limit)
    {
        // Get wishlists of similar users
        $wishlistProducts = DB::table('wishlists')
            ->whereIn('user_id', $similarUsers)
            ->whereNotIn('product_id', $excludeProductIds)
            ->pluck('product_id')
            ->toArray();

        // Get orders of similar users
        $orderProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.user_id', $similarUsers)
            ->whereNotIn('order_items.product_id', $excludeProductIds)
            ->pluck('order_items.product_id')
            ->toArray();

        $allRecommendedProducts = array_merge($wishlistProducts, $orderProducts);
        $productCounts = array_count_values($allRecommendedProducts);

        // Sort by most popular among similar users
        arsort($productCounts);

        return array_slice(array_keys($productCounts), 0, $limit);
    }

    private function getTopSellingProductIds(int $limit)
    {
        // Fallback to top selling
        $topProductIds = DB::table('order_items')
            ->select('product_id', DB::raw('SUM(qty) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->pluck('product_id')
            ->toArray();

        if (empty($topProductIds)) {
            return Product::where('stock', '>', 0)->inRandomOrder()->take($limit)->pluck('id')->toArray();
        }

        return $topProductIds;
    }
}
