# Laravel E2E Testing dengan Playwright

Dokumen ini memandu penulisan pengujian *end-to-end* (E2E) menggunakan Playwright khusus untuk proyek **Tanjung Jaya Corporation**. 
Pengujian difokuskan pada stabilitas aplikasi dan simulasi perjalanan (*user journey*) sesuai dengan tipe pengguna (role).

## 1. Struktur Folder
Semua *file* E2E testing harus berada di dalam folder `tests/e2e/`.

```
tests/e2e/
├── fixtures/          # Pengaturan fixture untuk role (auth state)
├── page-objects/      # Abstraksi halaman (POM)
├── helpers/           # Utilitas tambahan (contoh: database/seeder helper)
├── customer/          # Test spec untuk modul customer
├── admin/             # Test spec untuk modul admin
├── gudang/            # Test spec untuk modul gudang
└── manager/           # Test spec untuk modul manager
```

## 2. Konvensi Penamaan File
- Setiap *test spec* harus berakhiran dengan `.spec.ts`.
- Nama *file* harus mencerminkan modul atau fitur yang diuji secara spesifik, menggunakan kebab-case. 
- Contoh: `admin-create-category.spec.ts`, `customer-checkout.spec.ts`.

## 3. Akun Seeder (Role)
Untuk mempercepat login dan menjaga pengujian tetap deterministik, gunakan akun yang sudah digenerate dari `UserSeeder`.
Password standar untuk semua akun seeder adalah: `password`

Daftar Akun:
- **Admin**: `admin@tanjungjaya.com`
- **Manager**: `manager@tanjungjaya.com`
- **Gudang**: `gudang@tanjungjaya.com`
- **Customer**: `customer@tanjungjaya.com`

> **Helper Database**: Anda dapat membuat helper di `tests/e2e/helpers/db.ts` menggunakan modul `mysql2` atau API khusus aplikasi untuk melakukan *query* ke database guna mengambil data seeder dinamis (contoh: `SELECT * FROM users WHERE role = 'Customer' LIMIT 1`).

## 4. Konsep Fixtures untuk Authenticated State
Gunakan kapabilitas Playwright `test.use({ storageState: '...' })` atau kustomisasi *fixture* untuk melakukan *setup* otentikasi berdasarkan *role*.

**Contoh Fixture Sederhana (`tests/e2e/fixtures/auth.fixture.ts`):**
```typescript
import { test as base } from '@playwright/test';
import { LoginPage } from '../page-objects/LoginPage';

export const test = base.extend<{ 
  asAdmin: void, 
  asCustomer: void 
}>({
  asAdmin: async ({ page }, use) => {
    const loginPage = new LoginPage(page);
    await loginPage.goto();
    await loginPage.login('admin@tanjungjaya.com', 'password');
    await use();
  },
  asCustomer: async ({ page }, use) => {
    const loginPage = new LoginPage(page);
    await loginPage.goto();
    await loginPage.login('customer@tanjungjaya.com', 'password');
    await use();
  },
});
```

Di dalam file `.spec.ts`:
```typescript
import { test } from '../fixtures/auth.fixture';
import { expect } from '@playwright/test';

test('Admin dapat melihat dashboard', async ({ page, asAdmin }) => {
    await page.goto('/admin/dashboard');
    await expect(page).toHaveTitle(/Dashboard/);
});
```

## 5. Page Object Model (POM)
Gunakan pola *Page Object Model* (POM) untuk halaman yang sering digunakan berulang (seperti halaman Login, Dashboard, dan Keranjang). Letakkan *file* POM di `tests/e2e/page-objects/`.

**Contoh `tests/e2e/page-objects/LoginPage.ts`:**
```typescript
import { expect, type Locator, type Page } from '@playwright/test';

export class LoginPage {
  readonly page: Page;
  readonly emailInput: Locator;
  readonly passwordInput: Locator;
  readonly loginButton: Locator;

  constructor(page: Page) {
    this.page = page;
    this.emailInput = page.locator('input[name="email"]');
    this.passwordInput = page.locator('input[name="password"]');
    this.loginButton = page.locator('button[type="submit"]');
  }

  async goto() {
    await this.page.goto('/login');
  }

  async login(email: string, pass: string) {
    await this.emailInput.fill(email);
    await this.passwordInput.fill(pass);
    await this.loginButton.click();
    await this.page.waitForURL(/dashboard|\//); // Tunggu sampai redirect selesai
  }
}
```

## 6. Tips & Aturan Tambahan
- **Assertion**: Jangan gunakan sembarang penundaan (`page.waitForTimeout()`). Gunakan locators yang mendukung *auto-waiting* (contoh: `expect(locator).toBeVisible()`).
- **Data Kebersihan**: Sebisa mungkin, jalankan *seed* database yang *fresh* sebelum pengujian, atau pastikan tes E2E merapikan kembali (menghapus/memulihkan) data yang mereka buat (`teardown`).
- **Test Trace & Screenshots**: Secara bawaan, Playwright di proyek ini sudah diatur untuk menyimpan *Trace*, merekam *Video*, dan mengambil *Screenshot* (pada saat gagal) ke folder `docs/testing/playwright-report/`. Cek *artifact* ini bila *test pipeline* menemui kegagalan.

## 7. Cheatsheet Eksekusi Playwright
Berikut adalah daftar perintah penting untuk menjalankan dan melakukan *debugging* pada tes Playwright:

- **Jalankan seluruh test e2e (secara background/headless)**:
  ```bash
  npx playwright test
  ```
- **Jalankan satu file saja (contoh `produk.spec.ts`)**:
  ```bash
  npx playwright test produk.spec.ts
  ```
- **Jalankan dengan tampilan browser terlihat (untuk debugging visual)**:
  ```bash
  npx playwright test --headed
  ```
- **Jalankan satu test spesifik dalam mode debug interaktif**:
  ```bash
  npx playwright test --debug
  ```
- **Buka laporan HTML setelah eksekusi selesai**:
  ```bash
  npx playwright show-report docs/testing/playwright-report
  ```
- **Buka trace viewer untuk satu test yang spesifik (sangat berguna untuk test yang gagal)**:
  ```bash
  npx playwright show-trace docs/testing/playwright-results/<nama-folder-test>/trace.zip
  ```
