import { expect } from '@playwright/test';
import { test } from '../fixtures/auth.fixture';

test.describe('Customer Wishlist', () => {
  test('View Wishlist', async ({ page, asCustomer }) => {
    // Route resource is 'wishlists'
    await page.goto('/wishlists');
    await expect(page.locator('h2', { hasText: /Wishlist/i })).toBeVisible();
  });
});
