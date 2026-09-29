<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class);
    }

    public function userActivities()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function monthlySales()
    {
        return $this->hasMany(MonthlyProductSale::class);
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    /**
     * Catat mutasi stok (in/out/adjustment) untuk audit inventori.
     */
    public function logStock(string $type, int $qty, ?int $userId = null): void
    {
        $this->stockLogs()->create([
            'user_id' => $userId ?? auth()->id(),
            'type' => $type,
            'qty' => $qty,
        ]);
    }

    public function scopeSearchName($query, $name)
    {
        if ($name) {
            return $query->where('name', 'like', '%'.$name.'%');
        }

        return $query;
    }

    public function scopeFilterCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }

        return $query;
    }

    public function scopeFilterPrice($query, $min, $max)
    {
        if ($min !== null && $min !== '' && $max !== null && $max !== '') {
            return $query->whereBetween('price', [$min, $max]);
        } elseif ($min !== null && $min !== '') {
            return $query->where('price', '>=', $min);
        } elseif ($max !== null && $max !== '') {
            return $query->where('price', '<=', $max);
        }

        return $query;
    }
}
