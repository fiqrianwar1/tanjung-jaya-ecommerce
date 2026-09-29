import { expect } from '@playwright/test';
import { test } from './fixtures/auth.fixture';

/**
 * Regresi: pendaftaran akun baru harus berhasil tersimpan dengan role Customer.
 * Sebelumnya kolom `role` tidak diisi saat register -> SQL error 1364.
 */
test.describe('Registrasi akun baru', () => {
  test('user baru terdaftar & langsung masuk sebagai Customer', async ({ page }) => {
    const email = `tester.${Date.now()}@tanjungjaya.test`;

    await page.goto('/register');
    await page.fill('#name', 'Tester Perbaikan');
    await page.fill('#email', email);
    await page.fill('#password', 'password123');
    await page.fill('#password_confirmation', 'password123');

    await Promise.all([
      page.waitForLoadState('networkidle'),
      page.click('button[type=submit]'),
    ]);

    // Setelah register, customer diarahkan ke katalog (bukan /login atau error 500)
    expect(page.url()).not.toContain('/register');
    expect(page.url()).not.toContain('/login');

    await expect(page.locator('body')).toContainText('Customer');
    console.log('URL setelah register:', page.url());
  });
});