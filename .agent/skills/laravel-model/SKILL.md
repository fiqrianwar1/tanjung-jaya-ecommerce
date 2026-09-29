# Skill: Pembuatan Model Eloquent (Tanjung Jaya)

**Deskripsi:**
Panduan standar yang wajib diikuti oleh Agent ketika membuat atau memodifikasi Model Eloquent dalam proyek e-commerce "Tanjung Jaya". Dokumen ini memastikan seluruh model memiliki keamanan data yang seragam dan konvensi penamaan relasi yang tepat.

## 1. Lokasi & Boilerplate
- Seluruh Model harus diletakkan di dalam folder `app/Models/`.
- Setiap Model wajib menggunakan *namespace* `App\Models` dan men-*trait* `HasFactory`.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NamaModel extends Model {
    use HasFactory;
    // ...
}
```

## 2. Proteksi Mass Assignment
Untuk mempercepat *development* namun tetap mencegah *MassAssignmentException*, proyek ini menggunakan pendekatan *Guarded Empty Array* pada model standar, alih-alih mendefinisikan `$fillable` yang panjang.
- **Kecuali `User.php`:** Model otentikasi wajib menggunakan atribut `#[Fillable]` sesuai bawaan Laravel 11 (`#[Fillable(['name', 'email', 'password', 'role'])]`).
- **Model Lainnya:** Selalu gunakan `protected $guarded = [];`.

```php
class Product extends Model {
    use HasFactory;
    
    // Mengizinkan semua kolom untuk diisi (Mass Assignment)
    protected $guarded = [];
}
```

## 3. Konvensi Penamaan Relasi
Penamaan fungsi relasi (ORM) wajib mematuhi aturan kapitalisasi *camelCase* standar Laravel:

### A. One-to-Many / Many-to-Many (Mengembalikan Collection)
- Nama fungsi harus **Jamak (Plural)**.
- Contoh relasi *hasMany*: `public function orderItems()`
- Contoh relasi *belongsToMany*: `public function wishlistedProducts()`

### B. Belongs-To / Has-One (Mengembalikan Single Instance)
- Nama fungsi harus **Tunggal (Singular)**.
- Contoh relasi *belongsTo*: `public function category()`
- Contoh relasi *hasOne*: `public function returnRequest()`

## 4. Standar Return Type (Opsional namun Disarankan)
Meskipun tidak wajib, mendefinisikan *class* target secara eksplisit menggunakan `::class` adalah keharusan untuk menghindari *typo* *string*.
```php
// BENAR
public function category() { 
    return $this->belongsTo(Category::class); 
}

// SALAH (Rentan typo)
public function category() { 
    return $this->belongsTo('App\Models\Category'); 
}
```

## 5. Kriteria Keberhasilan (Success Criteria)
- Model dapat di-inisialisasi via Tinker (`php artisan tinker`).
- Pemanggilan relasi (contoh: `Product::first()->category->name`) sukses mengembalikan nilai tanpa *error* `Call to undefined relationship`.
