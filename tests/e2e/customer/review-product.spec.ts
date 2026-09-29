import { expect } from '@playwright/test';
import { test } from '../fixtures/auth.fixture';

test.describe('Customer Review Product', () => {
  const testComment = `Keren banget! Pengiriman cepat. ${Date.now()}`;

  test('Give review to a completed order and verify on product page', async ({ page, asCustomer }) => {
    // 1. Pergi ke halaman Pesanan Saya
    await page.goto('/orders');
    await expect(page.locator('h2', { hasText: /Riwayat Pesanan|Pesanan Saya/i })).toBeVisible();

    // Pastikan pesanan dengan status 'Selesai' (completed) ada
    await expect(page.locator('text=Selesai').first()).toBeVisible();

    // 2. Isi form ulasan
    // Kita ambil form rating pertama yang muncul
    const reviewForm = page.locator('form[action*="reviews"]').first();
    await expect(reviewForm).toBeVisible();

    // Pilih rating 5 Bintang
    await reviewForm.locator('select[name="rating"]').selectOption('5');
    
    // Tulis komentar
    await reviewForm.locator('textarea[name="comment"]').fill(testComment);

    // Ambil product_id agar kita tau mau redirect kemana nanti untuk ngecek (opsional, karena kita bisa klik link dari nama produk)
    // Tapi lebih gampang klik nama produk di atas form
    const productLink = reviewForm.locator('..').locator('..').locator('h4.font-bold').first();
    const productName = await productLink.innerText();

    // Submit form
    await reviewForm.locator('button', { name: /Submit Ulasan/i }).click();

    // Tunggu redirect kembali dan ada pesan sukses
    await expect(page.locator('text=Ulasan berhasil disimpan!')).toBeVisible();

    // 3. Verifikasi ulasan di halaman detail produk
    // Pergi ke halaman produk tersebut
    await page.goto('/carts'); // sekedar trigger ke halamann lain biar gampang
    await page.goto('/'); // Balik ke katalog utama untuk nyari produknya

    // Cari produk di katalog dan klik (Asumsi ada link ke detail)
    // Atau yang lebih pasti, langsung gunakan url produk berdasarkan id bila kita sempat ekstrak
    // Untuk amannya, kita buka halaman pesanan lagi, lalu klik judul produknya
    await page.goto('/orders');
    const orderItemCard = page.locator('div', { hasText: productName }).filter({ has: page.locator('text=Selesai') }).first();
    // Wait, nama produk di index tidak di-link. Let's see resources/views/customer/orders/index.blade.php
    // Oh, di index.blade.php nama produk cuma <h4 class="font-bold text-slate-800">{{ $item->product->name }}</h4>
    // Kita harus nyari link ke produk. Karena ga di-link, kita langsung pakai selector pencarian di homepage.
    await page.goto('/');
    await page.locator('a', { hasText: productName }).first().click();

    // Di halaman detail produk, cari seksi ulasan
    await expect(page.locator('h2', { hasText: 'Ulasan Pelanggan' })).toBeVisible();

    // Verifikasi komentar kita muncul
    await expect(page.locator('p', { hasText: testComment })).toBeVisible();
  });
});
