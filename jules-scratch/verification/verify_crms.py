from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch()
    context = browser.new_context()
    page = context.new_page()

    base_url = "http://127.0.0.1:8000"

    try:
        # 1. Verify the public homepage
        print("Navigating to the homepage...")
        page.goto(f"{base_url}/public/index.php")
        expect(page.get_by_role("heading", name="Welcome to the Car Rental Management System")).to_be_visible()
        page.screenshot(path="jules-scratch/verification/01_homepage.png")
        print("Homepage screenshot captured.")

        # 2. Verify the car listings page
        print("Navigating to the car listings page...")
        page.get_by_role("link", name="Browse Available Cars").click()
        expect(page).to_have_url(f"{base_url}/public/cars.php")
        expect(page.get_by_role("heading", name="Our Fleet")).to_be_visible()
        page.screenshot(path="jules-scratch/verification/02_car_listings.png")
        print("Car listings screenshot captured.")

        # 3. Verify the admin login page
        print("Navigating to the admin login page...")
        # There's no direct link, so we'll go there by URL
        page.goto(f"{base_url}/admin/login.php")
        expect(page.get_by_role("heading", name="Admin Login")).to_be_visible()
        page.screenshot(path="jules-scratch/verification/03_admin_login.png")
        print("Admin login screenshot captured.")

        print("Verification script completed successfully.")

    except Exception as e:
        print(f"An error occurred: {e}")
        page.screenshot(path="jules-scratch/verification/error.png")

    finally:
        browser.close()

with sync_playwright() as playwright:
    run(playwright)