import { expect } from '@playwright/test';
import { test } from '../fixtures/auth.fixture';

test.describe('Customer Cart', () => {
  test('View Cart', async ({ page, asCustomer }) => {
    // According to web.php, the route resource is 'carts' but without customer prefix
    await page.goto('/carts');
    await expect(page.locator('h2', { hasText: /Keranjang/i })).toBeVisible();
  });
});
