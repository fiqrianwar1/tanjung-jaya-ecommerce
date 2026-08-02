# Daftar Activity Diagram - Sistem E-Commerce Tanjung Jaya

Berikut adalah daftar 23 Activity Diagram (UML) yang merepresentasikan alur kerja sistem. File `.puml` dapat dirender menggunakan ekstensi PlantUML di VS Code (`Alt + D`) atau via [PlantText](https://www.planttext.com/).

## A. Customer
1. `activity-lihat-katalog.puml`: Alur mencari dan melihat detail produk.
2. `activity-rekomendasi-ai.puml`: Alur sistem menampilkan rekomendasi berbasis *Machine Learning*.
3. `activity-checkout.puml`: Alur keranjang, ongkos kirim (API RajaOngkir), hingga pembayaran (Midtrans).
4. `activity-lacak-pesanan.puml`: Alur melacak status pengiriman dan nomor resi.
5. `activity-ulasan-rating.puml`: Alur pemberian *review* setelah pesanan selesai.
6. `activity-ajukan-retur.puml`: Alur pembuatan RMA (Return Merchandise Auth).
7. `activity-chatbot-ai.puml`: Alur tanya jawab otomatis dan pelacakan resi via AI.

## B. Admin
8. `activity-kelola-produk.puml`: Alur CRUD data produk.
9. `activity-verifikasi-pesanan.puml`: Alur pengecekan bukti transfer dan penerusan ke gudang.
10. `activity-kelola-pelanggan.puml`: Alur pemantauan data pengguna.
11. `activity-moderasi-ulasan.puml`: Alur membalas dan menghapus *spam review*.
12. `activity-persetujuan-retur.puml`: Alur *decision* admin untuk menyetujui atau menolak retur.
13. `activity-audit-trail.puml`: Alur pemantauan rekam jejak sistem.
14. `activity-impersonation.puml`: Alur *Login As* pelanggan untuk investigasi/troubleshooting.

## C. Gudang (Warehouse)
15. `activity-kelola-stok.puml`: Alur update stok fisik aktual.
16. `activity-proses-pengiriman.puml`: Alur *packing* pesanan dan update resi (bisa secara *batch*).
17. `activity-terima-retur.puml`: Alur pengecekan barang rusak yang dikirim ulang oleh pelanggan.

## D. Manager
18. `activity-lihat-dashboard.puml`: Alur pemuatan metrik dan grafik penjualan.
19. `activity-laporan-penjualan.puml`: Alur *generate* dan ekspor laporan penjualan bulanan.
20. `activity-laporan-retur.puml`: Alur analisis tingkat kecacatan produk.
21. `activity-laporan-inventaris.puml`: Alur deteksi *early-warning* stok habis.
22. `activity-langganan-laporan.puml`: Alur *subscribe* laporan email otomatis.

## E. Sistem / Cron
23. `activity-abandoned-cart.puml`: Alur deteksi keranjang lama dan pengiriman *reminder* otomatis.

---

## Tentang Sequence Diagram (Laravel MVC)
Sebagai kelanjutan teknis, di direktori ini juga terdapat **23 file Sequence Diagram** (`sequence-*.puml`) yang namanya identik dengan file Activity Diagram di atas. 

Sequence diagram ini memetakan seluruh langkah kerja tersebut ke dalam Lifeline / arsitektur teknis **Laravel MVC**, meliputi:
- `View` (Blade UI)
- `Controller` (Laravel Controller)
- `Model` (Eloquent ORM)
- `DB` (MySQL)
- `Service` (Third-party API seperti Midtrans/RajaOngkir, Cron Job, dll).

Setiap *Decision Node* pada Activity Diagram diubah menjadi blok kondisional `alt/else` secara teknis.

---

## Class Diagram (ERD & Object Mapping)
Seluruh identitas objek, relasi, dan fungsi dari puluhan Use Case di atas telah disintesis ke dalam satu desain arsitektur data.

- **`class-diagram.puml`**: File ini merepresentasikan seluruh tabel dan model di sistem MVC, lengkap dengan tipe data (Atribut), fungsi (Method), dan kardinalitas relasi antar model (seperti *User has many Orders*). 
- Diagram ini akan menjadi fondasi langsung untuk pembuatan file *Migration* di Laravel.
