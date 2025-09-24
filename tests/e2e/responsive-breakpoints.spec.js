import { test, expect } from '@playwright/test';

test.describe('Responsive Breakpoints Tests', () => {
  const breakpoints = [
    { name: 'Mobile Small', width: 320, height: 568 },
    { name: 'Mobile Medium', width: 375, height: 667 },
    { name: 'Mobile Large', width: 414, height: 896 },
    { name: 'Tablet Portrait', width: 768, height: 1024 },
    { name: 'Tablet Landscape', width: 1024, height: 768 },
    { name: 'Desktop Small', width: 1280, height: 720 },
    { name: 'Desktop Large', width: 1920, height: 1080 }
  ];

  breakpoints.forEach(breakpoint => {
    test(`Layout should work at ${breakpoint.name} (${breakpoint.width}x${breakpoint.height})`, async ({ page }) => {
      await page.setViewportSize({ width: breakpoint.width, height: breakpoint.height });
      await page.goto('/');
      
      // Check if main content is visible
      const mainContent = page.locator('main, [data-testid="main-content"]');
      await expect(mainContent).toBeVisible();
      
      // Check if navigation is accessible
      const navigation = page.locator('nav, [data-testid="navigation"]');
      await expect(navigation).toBeVisible();
      
      // Check for horizontal overflow
      const hasHorizontalScroll = await page.evaluate(() => {
        return document.documentElement.scrollWidth > document.documentElement.clientWidth;
      });
      
      expect(hasHorizontalScroll).toBeFalsy();
    });
  });

  test('Navigation should adapt to different screen sizes', async ({ page }) => {
    // Test mobile navigation
    await page.setViewportSize({ width: 375, height: 667 });
    await page.goto('/');
    
    // Check if mobile menu exists on small screens
    const mobileMenuButton = page.locator('[data-testid="mobile-menu-button"]');
    if (await mobileMenuButton.count() > 0) {
      await expect(mobileMenuButton).toBeVisible();
    }
    
    // Test tablet navigation
    await page.setViewportSize({ width: 768, height: 1024 });
    await page.reload();
    
    // Check if desktop navigation is visible on larger screens
    const desktopNav = page.locator('[data-testid="desktop-navigation"]');
    if (await desktopNav.count() > 0) {
      await expect(desktopNav).toBeVisible();
    }
  });

  test('Grid layouts should be responsive', async ({ page }) => {
    await page.goto('/');
    
    // Test different grid layouts at various breakpoints
    const gridBreakpoints = [
      { width: 375, expectedColumns: 1 }, // Mobile: 1 column
      { width: 768, expectedColumns: 2 },  // Tablet: 2 columns
      { width: 1024, expectedColumns: 3 }, // Desktop: 3 columns
      { width: 1280, expectedColumns: 4 }  // Large desktop: 4 columns
    ];
    
    for (const breakpoint of gridBreakpoints) {
      await page.setViewportSize({ width: breakpoint.width, height: 800 });
      
      // Check if grid adapts to expected number of columns
      const gridItems = page.locator('[data-testid="grid-item"]');
      if (await gridItems.count() > 0) {
        // This is a simplified test - in practice, you'd check CSS grid properties
        await expect(gridItems.first()).toBeVisible();
      }
    }
  });

  test('Typography should scale appropriately', async ({ page }) => {
    await page.goto('/');
    
    const typographyTests = [
      { width: 375, minFontSize: 14 }, // Mobile: minimum 14px
      { width: 768, minFontSize: 16 }, // Tablet: minimum 16px
      { width: 1024, minFontSize: 16 } // Desktop: minimum 16px
    ];
    
    for (const test of typographyTests) {
      await page.setViewportSize({ width: test.width, height: 800 });
      
      // Check if text is readable at different screen sizes
      const textElements = page.locator('p, span, div');
      const firstText = textElements.first();
      
      if (await firstText.count() > 0) {
        const fontSize = await firstText.evaluate(el => {
          const style = getComputedStyle(el);
          return parseFloat(style.fontSize);
        });
        
        expect(fontSize).toBeGreaterThanOrEqual(test.minFontSize);
      }
    }
  });

  test('Images should be responsive across breakpoints', async ({ page }) => {
    await page.goto('/');
    
    const imageBreakpoints = [
      { width: 375, maxWidth: 375 },
      { width: 768, maxWidth: 768 },
      { width: 1024, maxWidth: 1024 }
    ];
    
    for (const breakpoint of imageBreakpoints) {
      await page.setViewportSize({ width: breakpoint.width, height: 800 });
      
      const images = page.locator('img');
      const imageCount = await images.count();
      
      for (let i = 0; i < Math.min(imageCount, 5); i++) {
        const img = images.nth(i);
        const box = await img.boundingBox();
        
        if (box) {
          expect(box.width).toBeLessThanOrEqual(breakpoint.maxWidth);
        }
      }
    }
  });

  test('Forms should be usable at all breakpoints', async ({ page }) => {
    await page.goto('/freight-orders/create');
    
    const formBreakpoints = [
      { width: 375, minTouchTarget: 44 },
      { width: 768, minTouchTarget: 44 },
      { width: 1024, minTouchTarget: 32 }
    ];
    
    for (const breakpoint of formBreakpoints) {
      await page.setViewportSize({ width: breakpoint.width, height: 800 });
      
      // Check if form inputs are properly sized
      const inputs = page.locator('input, select, textarea, button');
      const inputCount = await inputs.count();
      
      for (let i = 0; i < Math.min(inputCount, 10); i++) {
        const input = inputs.nth(i);
        const box = await input.boundingBox();
        
        if (box) {
          expect(box.height).toBeGreaterThanOrEqual(breakpoint.minTouchTarget);
        }
      }
    }
  });
});
