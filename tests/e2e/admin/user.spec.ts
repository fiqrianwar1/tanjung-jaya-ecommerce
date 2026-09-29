import { expect } from '@playwright/test';
import { test } from '../fixtures/auth.fixture';

test.describe('Admin User', () => {
  test('Read Users', async ({ page, asAdmin }) => {
    await page.goto('/admin/users');
    await expect(page.locator('h2', { hasText: /Daftar Pengguna/i })).toBeVisible();
  });
});
