import os
from playwright.sync_api import sync_playwright, Page, expect
import pathlib

def verify_admin_dashboard(page: Page):
    """
    Navigates to the admin dashboard and takes a screenshot for visual verification.
    """
    # Construct the URL to the admin dashboard running on the local PHP server
    dashboard_url = "http://localhost:8000/admin/dashboard.php"

    print(f"Navigating to: {dashboard_url}")

    # Go to the page
    page.goto(dashboard_url, wait_until="domcontentloaded")

    # Wait for a key element on the dashboard to be visible to ensure the page has loaded
    expect(page.get_by_role("heading", name="Admin Dashboard")).to_be_visible(timeout=10000)

    # Take a screenshot and save it
    screenshot_path = "jules-scratch/verification/verification.png"
    page.screenshot(path=screenshot_path, full_page=True)

    print(f"Screenshot successfully saved to {screenshot_path}")

def main():
    """
    Main function to run the Playwright browser, execute the verification, and close the browser.
    """
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page()
        # Set a generous viewport size to capture the whole page layout
        page.set_viewport_size({"width": 1280, "height": 1024})
        try:
            verify_admin_dashboard(page)
        finally:
            browser.close()

if __name__ == "__main__":
    main()