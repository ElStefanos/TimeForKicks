from random import randint
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.firefox.options import Options
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.chrome.service import Service as ChromeService
from selenium.webdriver.chrome.options import Options
from selenium_stealth import stealth
import time as time

def stockx(search_query):

    options = Options()
    options.add_argument("--headless")
    options.add_experimental_option("excludeSwitches", ["enable-automation"])
    options.add_experimental_option('useAutomationExtension', False)

    driver = webdriver.Chrome(options=options, service=ChromeService(
    ChromeDriverManager().install()))

    version = randint(10, 99)

    stealth(driver,
        user_agent = f'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.{version} (KHTML, like Gecko) Chrome/110.0.5481.105 Safari/537.36',
        languages=["en-US", "en"],
        vendor="Google Inc.",
        platform="Win32",
        webgl_vendor="Intel Inc.",
        renderer="Intel Iris OpenGL Engine",
        fix_hairline=True,
        )

    driver.get(f"https://stockx.com/search?s={search_query}")

    driver.save_screenshot("stockx.png")

    try:

        grid = driver.find_element(By.CLASS_NAME, "css-c8gdzb")

        link_elements = grid.find_elements(By.CSS_SELECTOR, "a[data-testid='RouterSwitcherLink']")

        first_link = link_elements[0]

        first_link.click()
        
        driver.save_screenshot("stockx-product.png")

        image = driver.find_element(By.CLASS_NAME, "chakra-image").get_attribute("src")
        product_name = driver.find_element(By.CSS_SELECTOR, 'h1[data-component="primary-product-title"]')

        prices_labels = driver.find_elements(By.CLASS_NAME, "chakra-stat__label")
        prices = driver.find_elements(By.CLASS_NAME, "chakra-stat__number")

        prices_dict = {}

        for i in range(0, len(prices_labels) - 1):
            prices_dict[prices_labels[i].text] = prices[i].text

        print(product_name.text)
        print(image)
        print(prices_dict)

        driver.quit()
        blah = {"img": image, "name": product_name.text}
        return blah 

    except Exception as e:
        print("An error occurred:", e)
        
    finally:
        driver.quit()
        
stockx("Air max 90")