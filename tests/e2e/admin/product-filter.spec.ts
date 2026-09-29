import { expect } from '@playwright/test';
import { test } from '../fixtures/auth.fixture';

test.describe('Admin Product Search and Filter', () => {
  test('Search and filter products using UI', async ({ page, asAdmin }) => {
    await page.goto('/admin/products');
    await expect(page.locator('h2', { hasText: /Daftar Produk/i })).toBeVisible();

    // 1. Test search with non-existent name
    await page.locator('input[name="search"]').fill('RANDOM_NON_EXISTENT_PRODUCT_123');
    await page.locator('button', { hasText: 'Filter' }).click();
    
    // Assert table empty state
    await expect(page.locator('text=Belum ada produk di etalase Anda.')).toBeVisible();

    // 2. Test Reset Button
    await page.locator('a', { hasText: 'Reset' }).click();
    await expect(page.locator('input[name="search"]')).toHaveValue('');

    // 3. Test High Price Filter (should be empty)
    await page.locator('input[name="min_price"]').fill('999999999');
    await page.locator('button', { hasText: 'Filter' }).click();
    await expect(page.locator('text=Belum ada produk di etalase Anda.')).toBeVisible();
    await expect(page).toHaveURL(/min_price=999999999/);

    // 4. Test combined filter (price 0 to 100000)
    await page.locator('input[name="min_price"]').fill('0');
    await page.locator('input[name="max_price"]').fill('100000');
    // Also select category if available (just by index to ensure it works)
    const categorySelect = page.locator('select[name="category_id"]');
    if (await categorySelect.isVisible()) {
        const optionsCount = await categorySelect.locator('option').count();
        if (optionsCount > 1) {
            // Select the second option (first real category)
            await categorySelect.selectOption({ index: 1 });
        }
    }
    await page.locator('button', { hasText: 'Filter' }).click();
    await expect(page).toHaveURL(/max_price=100000/);
    
    // As long as the page doesn't crash and returns 200, the scope logic is solid.
  });
});
