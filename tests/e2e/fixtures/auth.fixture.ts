import { test as base } from '@playwright/test';
import { LoginPage } from '../page-objects/LoginPage';

export const test = base.extend<{ 
  asAdmin: void, 
  asCustomer: void,
  asGudang: void,
  asManager: void
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
  asGudang: async ({ page }, use) => {
    const loginPage = new LoginPage(page);
    await loginPage.goto();
    await loginPage.login('gudang@tanjungjaya.com', 'password');
    await use();
  },
  asManager: async ({ page }, use) => {
    const loginPage = new LoginPage(page);
    await loginPage.goto();
    await loginPage.login('manager@tanjungjaya.com', 'password');
    await use();
  },
});
