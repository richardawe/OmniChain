import { test, expect } from '@playwright/test';

test.describe('Mobile Responsiveness Tests', () => {
  test.beforeEach(async ({ page }) => {
    // Navigate to the main dashboard
    await page.goto('/');
  });

  test('Dashboard should be responsive on mobile devices', async ({ page }) => {
    // Set mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Check if the main navigation is accessible
    const navigation = page.locator('nav');
    await expect(navigation).toBeVisible();
    
    // Check if the dashboard content is visible
    const dashboardContent = page.locator('[data-testid="dashboard-content"]');
    await expect(dashboardContent).toBeVisible();
    
    // Check if mobile menu button exists (if implemented)
    const mobileMenuButton = page.locator('[data-testid="mobile-menu-button"]');
    if (await mobileMenuButton.count() > 0) {
      await expect(mobileMenuButton).toBeVisible();
    }
  });

  test('Navigation should work on mobile', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Test mobile navigation
    const mobileMenuButton = page.locator('[data-testid="mobile-menu-button"]');
    if (await mobileMenuButton.count() > 0) {
      await mobileMenuButton.click();
      
      // Check if mobile menu is visible
      const mobileMenu = page.locator('[data-testid="mobile-menu"]');
      await expect(mobileMenu).toBeVisible();
    }
  });

  test('Tables should be responsive on mobile', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Navigate to a page with tables (e.g., freight orders)
    await page.goto('/freight-orders');
    
    // Check if tables have horizontal scroll or are responsive
    const table = page.locator('table');
    if (await table.count() > 0) {
      await expect(table).toBeVisible();
      
      // Check if table has horizontal scroll on mobile
      const tableContainer = page.locator('[data-testid="table-container"]');
      if (await tableContainer.count() > 0) {
        const overflow = await tableContainer.evaluate(el => getComputedStyle(el).overflowX);
        expect(overflow).toBe('auto');
      }
    }
  });

  test('Forms should be mobile-friendly', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Navigate to a form page
    await page.goto('/freight-orders/create');
    
    // Check if form elements are properly sized for mobile
    const form = page.locator('form');
    if (await form.count() > 0) {
      await expect(form).toBeVisible();
      
      // Check if input fields are touch-friendly (minimum 44px height)
      const inputs = page.locator('input, select, textarea');
      const inputCount = await inputs.count();
      
      for (let i = 0; i < inputCount; i++) {
        const input = inputs.nth(i);
        const box = await input.boundingBox();
        if (box) {
          expect(box.height).toBeGreaterThanOrEqual(44);
        }
      }
    }
  });

  test('Buttons should be touch-friendly', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Check if buttons meet minimum touch target size (44x44px)
    const buttons = page.locator('button, [role="button"]');
    const buttonCount = await buttons.count();
    
    for (let i = 0; i < buttonCount; i++) {
      const button = buttons.nth(i);
      const box = await button.boundingBox();
      if (box) {
        expect(box.width).toBeGreaterThanOrEqual(44);
        expect(box.height).toBeGreaterThanOrEqual(44);
      }
    }
  });

  test('Text should be readable on mobile', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Check if text is not too small (minimum 16px to prevent zoom on iOS)
    const textElements = page.locator('p, span, div, h1, h2, h3, h4, h5, h6');
    const textCount = await textElements.count();
    
    for (let i = 0; i < Math.min(textCount, 10); i++) { // Check first 10 text elements
      const element = textElements.nth(i);
      const fontSize = await element.evaluate(el => {
        const style = getComputedStyle(el);
        return parseFloat(style.fontSize);
      });
      
      // Font size should be at least 16px for mobile readability
      expect(fontSize).toBeGreaterThanOrEqual(16);
    }
  });

  test('Images should be responsive', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Check if images have responsive attributes
    const images = page.locator('img');
    const imageCount = await images.count();
    
    for (let i = 0; i < imageCount; i++) {
      const img = images.nth(i);
      const maxWidth = await img.evaluate(el => getComputedStyle(el).maxWidth);
      
      // Images should not exceed viewport width
      expect(maxWidth).toBe('100%');
    }
  });

  test('Modal dialogs should be mobile-friendly', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Try to open a modal (if available)
    const modalTrigger = page.locator('[data-testid="modal-trigger"]');
    if (await modalTrigger.count() > 0) {
      await modalTrigger.click();
      
      const modal = page.locator('[data-testid="modal"]');
      await expect(modal).toBeVisible();
      
      // Check if modal is properly positioned on mobile
      const modalBox = await modal.boundingBox();
      if (modalBox) {
        expect(modalBox.width).toBeLessThanOrEqual(375); // Should fit mobile width
      }
    }
  });

  test('Sidebar should collapse on mobile', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Check if sidebar is hidden or collapsed on mobile
    const sidebar = page.locator('[data-testid="sidebar"]');
    if (await sidebar.count() > 0) {
      const isVisible = await sidebar.isVisible();
      const display = await sidebar.evaluate(el => getComputedStyle(el).display);
      
      // Sidebar should be hidden or collapsed on mobile
      expect(display === 'none' || !isVisible).toBeTruthy();
    }
  });

  test('Content should not overflow horizontally', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 667 });
    
    // Check if page content fits within viewport
    const body = page.locator('body');
    const bodyBox = await body.boundingBox();
    
    if (bodyBox) {
      expect(bodyBox.width).toBeLessThanOrEqual(375);
    }
    
    // Check for horizontal scroll
    const hasHorizontalScroll = await page.evaluate(() => {
      return document.documentElement.scrollWidth > document.documentElement.clientWidth;
    });
    
    expect(hasHorizontalScroll).toBeFalsy();
  });
});
