import { expect } from '@playwright/test';
import { test } from '../fixtures/auth.fixture';

test.describe('Admin Product', () => {
  test('Read Products', async ({ page, asAdmin }) => {
    await page.goto('/admin/products');
    await expect(page.locator('h2', { hasText: /Daftar Produk|Produk/i })).toBeVisible();
    await expect(page.locator('table')).toBeVisible();
  });
});
