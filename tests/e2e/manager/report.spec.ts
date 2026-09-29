import { expect } from '@playwright/test';
import { test } from '../fixtures/auth.fixture';

test.describe('Manager Report', () => {
  test('View Reports', async ({ page, asManager }) => {
    await page.goto('/manager/reports');
    await expect(page.locator('h2', { hasText: /Laporan/i })).toBeVisible();
  });
});
