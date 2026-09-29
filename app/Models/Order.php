<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returnRequest()
    {
        return $this->hasOne(ReturnRequest::class);
    }

    /**
     * Hitung ulang total pesanan dari seluruh order item.
     */
    public function recalculateTotal(): void
    {
        $subtotal = $this->orderItems()
            ->selectRaw('COALESCE(SUM(qty * price), 0) as total')
            ->value('total');

        $this->update([
            'total' => $subtotal + (float) $this->shipping_cost,
        ]);
    }

    /**
     * Total nilai barang saja (tanpa ongkir).
     */
    public function getSubtotalAttribute(): float
    {
        return (float) $this->orderItems->sum(fn ($item) => $item->qty * $item->price);
    }

    /**
     * Label status yang ramah dibaca (mendukung data lama berbahasa Indonesia).
     */
    public function getStatusLabelAttribute(): string
    {
        return [
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'returned' => 'Diretur',
            'cancelled' => 'Dibatalkan',
            'Menunggu Pembayaran' => 'Menunggu Pembayaran',
            'Diproses' => 'Diproses',
            'Dikirim' => 'Dikirim',
            'Selesai' => 'Selesai',
        ][$this->status] ?? $this->status;
    }
}
