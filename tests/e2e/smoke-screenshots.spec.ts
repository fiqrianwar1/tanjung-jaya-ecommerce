import { test, expect } from '@playwright/test';

/**
 * Smoke test menyeluruh: buka setiap halaman penting untuk tiap peran,
 * catat error console / request gagal, lalu simpan screenshot ke docs/screenshots.
 *
 * Jalankan dengan: npx playwright test tests/e2e/smoke-screenshots.spec.ts
 */

const SHOTS = 'docs/screenshots';

type Role = {
  name: string;
  email: string;
  pages: { path: string; label: string; expectText?: RegExp }[];
};

const roles: Role[] = [
  {
    name: 'guest',
    email: '',
    pages: [
      { path: '/', label: '01-katalog-guest', expectText: /Tanjung Jaya/i },
      { path: '/login', label: '02-login', expectText: /Masuk|Login/i },
      { path: '/register', label: '03-register', expectText: /Daftar|Bergabung/i },
    ],
  },
  {
    name: 'customer',
    email: 'customer@tanjungjaya.com',
    pages: [
      { path: '/', label: '10-katalog-customer', expectText: /Tanjung Jaya/i },
      { path: '/carts', label: '11-keranjang', expectText: /Keranjang/i },
      { path: '/orders', label: '12-pesanan-saya', expectText: /Pesanan/i },
      { path: '/wishlists', label: '13-wishlist', expectText: /Wishlist/i },
      { path: '/reviews', label: '14-ulasan-saya', expectText: /Ulasan/i },
      { path: '/returns', label: '15-retur-saya', expectText: /Retur/i },
      { path: '/profile', label: '16-profil', expectText: /Profil/i },
    ],
  },
  {
    name: 'admin',
    email: 'admin@tanjungjaya.com',
    pages: [
      { path: '/admin/products', label: '20-admin-produk', expectText: /Produk/i },
      { path: '/admin/products/create', label: '21-admin-produk-baru', expectText: /Tambah|Produk/i },
      { path: '/admin/categories', label: '22-admin-kategori', expectText: /Kategori/i },
      { path: '/admin/orders', label: '23-admin-pesanan', expectText: /Pesanan/i },
      { path: '/admin/returns', label: '24-admin-retur', expectText: /Retur/i },
      { path: '/admin/reviews', label: '25-admin-ulasan', expectText: /Ulasan/i },
      { path: '/admin/users', label: '26-admin-pengguna', expectText: /Pengguna|User/i },
      { path: '/admin/audit-logs', label: '27-admin-audit-log', expectText: /Audit/i },
    ],
  },
  {
    name: 'gudang',
    email: 'gudang@tanjungjaya.com',
    pages: [
      { path: '/gudang/stocks', label: '30-gudang-stok', expectText: /Stok/i },
      { path: '/gudang/orders', label: '31-gudang-pesanan', expectText: /Pesanan|Pengiriman/i },
      { path: '/gudang/returns', label: '32-gudang-retur', expectText: /Retur/i },
    ],
  },
  {
    name: 'manager',
    email: 'manager@tanjungjaya.com',
    pages: [
      { path: '/manager/dashboard', label: '40-manager-dashboard', expectText: /Dashboard|Pendapatan|Penjualan/i },
      { path: '/manager/reports', label: '41-manager-laporan', expectText: /Laporan/i },
      { path: '/manager/subscriptions', label: '42-manager-langganan', expectText: /Langganan/i },
    ],
  },
];

for (const role of roles) {
  test.describe(`Screenshot role: ${role.name}`, () => {
    for (const target of role.pages) {
      test(`${target.label}`, async ({ page }) => {
        const problems: string[] = [];

        page.on('console', (msg) => {
          if (msg.type() === 'error') problems.push(`[console] ${msg.text()}`);
        });
        page.on('pageerror', (err) => problems.push(`[pageerror] ${err.message}`));
        page.on('requestfailed', (req) => {
          const url = req.url();
          if (!url.includes('googleapis') && !url.includes('unsplash')) {
            problems.push(`[requestfailed] ${url} — ${req.failure()?.errorText}`);
          }
        });
        page.on('response', (res) => {
          if (res.status() >= 500) problems.push(`[http ${res.status()}] ${res.url()}`);
        });

        if (role.email) {
          await page.goto('/login');
          await page.fill('#email', role.email);
          await page.fill('#password', 'password');
          await Promise.all([
            page.waitForLoadState('networkidle'),
            page.click('button[type=submit]'),
          ]);
        }

        const response = await page.goto(target.path, { waitUntil: 'networkidle' });
        expect(response?.status(), `HTTP status ${target.path}`).toBeLessThan(400);

        if (target.expectText) {
          await expect(page.locator('body')).toContainText(target.expectText);
        }

        await page.screenshot({ path: `${SHOTS}/${target.label}.png`, fullPage: true });

        if (problems.length) {
          console.log(`\n=== MASALAH di ${target.path} (${role.name}) ===`);
          problems.forEach((p) => console.log(' - ' + p));
        }
      });
    }
  });
}

test.describe('Chatbot', () => {
  test('kirim pesan & dapat balasan dari katalog', async ({ page }) => {
    const consoleErrors: string[] = [];
    page.on('console', (msg) => {
      if (msg.type() === 'error') consoleErrors.push(msg.text());
    });

    await page.goto('/', { waitUntil: 'networkidle' });

    // Buka widget chatbot
    await page.click('button[aria-label="Buka chat Tanjung Jaya"]');
    await expect(page.getByText('Tanjung Jaya AI')).toBeVisible();

    await page.fill('input[aria-label="Tulis pesan untuk asisten Tanjung Jaya"]', 'produk apa saja yang tersedia?');

    const chatResponse = page.waitForResponse(
      (res) => res.url().includes('/chat') && res.request().method() === 'POST',
      { timeout: 60000 },
    );

    await page.keyboard.press('Enter');
    const res = await chatResponse;

    console.log('STATUS /chat =', res.status());
    const body = await res.json().catch(() => ({}));
    console.log('BALASAN BOT =', JSON.stringify(body).slice(0, 400));

    await page.waitForTimeout(1500);
    await page.screenshot({ path: `${SHOTS}/50-chatbot-jawaban.png` });

    expect(res.status()).toBe(200);
    expect((body as any).response?.length ?? 0).toBeGreaterThan(5);

    if (consoleErrors.length) {
      console.log('CONSOLE ERRORS:', consoleErrors.join(' | '));
    }
  });
});