import { expect } from '@playwright/test';
import { test } from '../fixtures/auth.fixture';

test.describe('Gudang Stock', () => {
  test('Manage Stock', async ({ page, asGudang }) => {
    await page.goto('/gudang/stocks');
    await expect(page.locator('h2', { hasText: /Log Stok/i })).toBeVisible();
  });
});
