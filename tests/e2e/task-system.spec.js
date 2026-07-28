import { test, expect } from '@playwright/test';

test.describe('Task Management System E2E Suite', () => {

  test('User authentication flow (Register, Logout, Login)', async ({ page }) => {
    const timestamp = Date.now();
    const email = `playwright_${timestamp}@example.com`;

    // 1. Go to register
    await page.goto('/register');
    await page.fill('#name', 'Playwright User');
    await page.fill('#email', email);
    await page.fill('#password', 'Password123!');
    await page.fill('#password_confirmation', 'Password123!');
    await page.click('button[type="submit"]');

    // Should redirect to projects dashboard
    await expect(page).toHaveURL(/\/projects/);

    // 2. Log out
    await page.click('button:has-text("Playwright User")');
    await page.click('a:has-text("Log Out")');

    // Should redirect to login
    await expect(page).toHaveURL(/\/login/);

    // 3. Log in again
    await page.fill('#email', email);
    await page.fill('#password', 'Password123!');
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL(/\/projects/);
  });

  test('Project CRUD and Task status filtering', async ({ page }) => {
    // Log in with pre-seeded demo user
    await page.goto('/login');
    await page.fill('#email', 'demo@example.com');
    await page.fill('#password', 'password');
    await page.click('button[type="submit"]');

    // Create a new project
    await page.click('a:has-text("+ Create New Project")');
    await page.fill('#name', 'Playwright Automated Project');
    await page.fill('#description', 'Testing project creation with Playwright');
    await page.click('button:has-text("Save Project")');

    // Verify project detail header
    await expect(page.locator('h2')).toContainText('Playwright Automated Project');

    // Add a new task
    await page.fill('#title', 'Automated E2E Test Task');
    await page.fill('#description', 'Task added automatically via Playwright');
    await page.selectOption('#status', 'In Progress');
    await page.click('button:has-text("Add")');

    // Verify task displays in list
    await expect(page.locator('body')).toContainText('Automated E2E Test Task');

    // Test filter tabs
    await page.click('a:has-text("Completed")');
    await expect(page.locator('body')).not.toContainText('Automated E2E Test Task');

    await page.click('a:has-text("In Progress")');
    await expect(page.locator('body')).toContainText('Automated E2E Test Task');
  });

  test('Server-side form validation error display', async ({ page }) => {
    // Log in
    await page.goto('/login');
    await page.fill('#email', 'demo@example.com');
    await page.fill('#password', 'password');
    await page.click('button[type="submit"]');

    // Go to project creation form
    await page.click('a:has-text("+ Create New Project")');
    await page.fill('#name', ''); // Leave empty
    await page.evaluate(() => document.querySelector('#name').removeAttribute('required'));
    await page.click('button:has-text("Save Project")');

    // Check validation error
    await expect(page.locator('body')).toContainText('The name field is required.');
  });

});
