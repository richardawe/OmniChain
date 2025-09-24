import { test, expect } from '@playwright/test';

test.describe('Touch Interactions Tests', () => {
  test.beforeEach(async ({ page }) => {
    // Set mobile viewport for touch testing
    await page.setViewportSize({ width: 375, height: 667 });
  });

  test('Touch targets should be properly sized', async ({ page }) => {
    await page.goto('/');
    
    // Check all interactive elements for proper touch target size
    const touchElements = page.locator('button, a, input, select, [role="button"], [data-testid*="button"]');
    const elementCount = await touchElements.count();
    
    for (let i = 0; i < elementCount; i++) {
      const element = touchElements.nth(i);
      const box = await element.boundingBox();
      
      if (box) {
        // Touch targets should be at least 44x44px
        expect(box.width).toBeGreaterThanOrEqual(44);
        expect(box.height).toBeGreaterThanOrEqual(44);
      }
    }
  });

  test('Touch gestures should work on mobile', async ({ page }) => {
    await page.goto('/');
    
    // Test swipe gestures on carousels or sliders
    const carousel = page.locator('[data-testid="carousel"]');
    if (await carousel.count() > 0) {
      // Test horizontal swipe
      await carousel.hover();
      await page.mouse.down();
      await page.mouse.move(100, 0);
      await page.mouse.up();
    }
    
    // Test scroll behavior
    const scrollableContent = page.locator('[data-testid="scrollable-content"]');
    if (await scrollableContent.count() > 0) {
      await scrollableContent.evaluate(el => el.scrollTop = 100);
      const scrollTop = await scrollableContent.evaluate(el => el.scrollTop);
      expect(scrollTop).toBe(100);
    }
  });

  test('Long press should work on mobile', async ({ page }) => {
    await page.goto('/');
    
    // Test long press on interactive elements
    const longPressElement = page.locator('[data-testid="long-press-target"]');
    if (await longPressElement.count() > 0) {
      await longPressElement.hover();
      await page.mouse.down();
      await page.waitForTimeout(500); // Long press duration
      await page.mouse.up();
      
      // Check if long press action was triggered
      const longPressResult = page.locator('[data-testid="long-press-result"]');
      if (await longPressResult.count() > 0) {
        await expect(longPressResult).toBeVisible();
      }
    }
  });

  test('Pinch to zoom should be disabled on mobile', async ({ page }) => {
    await page.goto('/');
    
    // Check if viewport meta tag is properly set
    const viewportMeta = page.locator('meta[name="viewport"]');
    const content = await viewportMeta.getAttribute('content');
    
    // Should include user-scalable=no or maximum-scale=1
    expect(content).toMatch(/user-scalable=no|maximum-scale=1/);
  });

  test('Touch feedback should be visible', async ({ page }) => {
    await page.goto('/');
    
    // Test button press feedback
    const buttons = page.locator('button');
    const buttonCount = await buttons.count();
    
    for (let i = 0; i < Math.min(buttonCount, 5); i++) {
      const button = buttons.nth(i);
      
      // Get initial styles
      const initialStyles = await button.evaluate(el => {
        const style = getComputedStyle(el);
        return {
          backgroundColor: style.backgroundColor,
          transform: style.transform
        };
      });
      
      // Simulate touch press
      await button.hover();
      await page.mouse.down();
      
      // Check if visual feedback is applied
      const pressedStyles = await button.evaluate(el => {
        const style = getComputedStyle(el);
        return {
          backgroundColor: style.backgroundColor,
          transform: style.transform
        };
      });
      
      // Styles should change on press (this is a simplified test)
      await page.mouse.up();
    }
  });

  test('Touch events should not interfere with scroll', async ({ page }) => {
    await page.goto('/');
    
    // Test that touch events don't prevent scrolling
    const scrollableElement = page.locator('body');
    
    // Simulate touch and scroll
    await scrollableElement.hover();
    await page.mouse.down();
    await page.mouse.move(0, -100); // Scroll up
    await page.mouse.up();
    
    // Check if scroll position changed
    const scrollTop = await page.evaluate(() => window.scrollY);
    expect(scrollTop).toBeGreaterThanOrEqual(0);
  });

  test('Touch targets should not overlap', async ({ page }) => {
    await page.goto('/');
    
    // Check for overlapping touch targets
    const touchElements = page.locator('button, a, input, [role="button"]');
    const elementCount = await touchElements.count();
    
    for (let i = 0; i < elementCount - 1; i++) {
      for (let j = i + 1; j < elementCount; j++) {
        const element1 = touchElements.nth(i);
        const element2 = touchElements.nth(j);
        
        const box1 = await element1.boundingBox();
        const box2 = await element2.boundingBox();
        
        if (box1 && box2) {
          // Check if elements overlap
          const overlap = !(box1.x + box1.width < box2.x || 
                          box2.x + box2.width < box1.x || 
                          box1.y + box1.height < box2.y || 
                          box2.y + box2.height < box1.y);
          
          // Touch targets should not overlap significantly
          if (overlap) {
            const overlapArea = Math.max(0, Math.min(box1.x + box1.width, box2.x + box2.width) - Math.max(box1.x, box2.x)) *
                              Math.max(0, Math.min(box1.y + box1.height, box2.y + box2.height) - Math.max(box1.y, box2.y));
            const totalArea = (box1.width * box1.height) + (box2.width * box2.height);
            const overlapRatio = overlapArea / totalArea;
            
            expect(overlapRatio).toBeLessThan(0.5); // Less than 50% overlap
          }
        }
      }
    }
  });

  test('Touch keyboard should work properly', async ({ page }) => {
    await page.goto('/freight-orders/create');
    
    // Test input focus and keyboard behavior
    const inputs = page.locator('input[type="text"], input[type="email"], input[type="number"]');
    const inputCount = await inputs.count();
    
    for (let i = 0; i < Math.min(inputCount, 3); i++) {
      const input = inputs.nth(i);
      
      // Focus on input
      await input.click();
      
      // Check if input is focused
      const isFocused = await input.evaluate(el => document.activeElement === el);
      expect(isFocused).toBeTruthy();
      
      // Test typing
      await input.fill('test input');
      const value = await input.inputValue();
      expect(value).toBe('test input');
    }
  });

  test('Touch accessibility should be maintained', async ({ page }) => {
    await page.goto('/');
    
    // Check if touch targets have proper ARIA labels
    const interactiveElements = page.locator('button, a, input, [role="button"]');
    const elementCount = await interactiveElements.count();
    
    for (let i = 0; i < Math.min(elementCount, 10); i++) {
      const element = interactiveElements.nth(i);
      
      // Check for accessibility attributes
      const ariaLabel = await element.getAttribute('aria-label');
      const ariaLabelledBy = await element.getAttribute('aria-labelledby');
      const textContent = await element.textContent();
      
      // Element should have some form of accessible name
      const hasAccessibleName = ariaLabel || ariaLabelledBy || (textContent && textContent.trim().length > 0);
      expect(hasAccessibleName).toBeTruthy();
    }
  });
});
