import { expect } from '@playwright/test';
import { test } from '../fixtures/auth.fixture';

test.describe.serial('Admin Categories CRUD', () => {
  const uniqueCategoryName = `Kategori Test ${Date.now()}`;
  const updatedCategoryName = `${uniqueCategoryName} Updated`;

  test('Create Category', async ({ page, asAdmin }) => {
    await page.goto('/admin/categories');
    
    // Check page title
    await expect(page.locator('h2', { hasText: 'Daftar Kategori' })).toBeVisible();

    // Click 'Tambah Kategori' button
    await page.getByRole('button', { name: /Tambah Kategori/i }).click();

    // Fill form in modal
    await page.getByLabel(/Nama Kategori/i).fill(uniqueCategoryName);
    
    // Submit form
    await page.getByRole('button', { name: 'Simpan Kategori' }).click();

    // Verify success message
    await expect(page.locator('text=Kategori berhasil ditambahkan!')).toBeVisible();
    
    // Verify category is in the table
    await expect(page.locator('td', { hasText: uniqueCategoryName }).first()).toBeVisible();
  });

  test('Read Categories', async ({ page, asAdmin }) => {
    await page.goto('/admin/categories');
    // Ensure table rows exist
    await expect(page.locator('table tbody tr').first()).toBeVisible();
    // Ensure the created category is visible
    await expect(page.locator('td', { hasText: uniqueCategoryName }).first()).toBeVisible();
  });

  test('Update Category', async ({ page, asAdmin }) => {
    await page.goto('/admin/categories');
    
    // Locate the row containing the category
    const row = page.locator('tr', { hasText: uniqueCategoryName });
    await expect(row).toBeVisible();

    // Click 'Edit' button in that row
    await row.getByRole('button', { name: 'Edit' }).click();

    // Fill new name
    await page.getByLabel(/Nama Kategori/i).fill(updatedCategoryName);
    
    // Submit form
    await page.getByRole('button', { name: 'Simpan Kategori' }).click();

    // Verify success message
    await expect(page.locator('text=Kategori berhasil diperbarui!')).toBeVisible();
    
    // Verify updated category is in the table
    await expect(page.locator('td', { hasText: updatedCategoryName }).first()).toBeVisible();
  });

  test('Delete Category', async ({ page, asAdmin }) => {
    await page.goto('/admin/categories');
    
    const row = page.locator('tr', { hasText: updatedCategoryName });
    await expect(row).toBeVisible();

    // Set up dialog handler for the 'confirm' prompt before clicking delete
    page.once('dialog', dialog => dialog.accept());

    // Click 'Hapus' button in that row
    await row.getByRole('button', { name: 'Hapus' }).click();

    // Verify success message
    await expect(page.locator('text=Kategori berhasil dihapus!')).toBeVisible();
    
    // Verify category is removed
    await expect(row).not.toBeVisible();
  });
});
