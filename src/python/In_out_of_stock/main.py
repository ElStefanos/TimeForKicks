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

from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

options = Options()
options.add_argument("--headless")
options.add_argument("--no-sandbox")
options.add_argument("enable-automation")
options.add_argument("--disable-dev-shm-usage")
options.add_argument("--window-size=1920x1080")
options.add_argument("--disable-notifications")

options.add_argument("--disable-extenstions")
options.add_argument("--dns-prefetch-disable")
options.add_argument("disable-infobars")
options.add_argument("force-device-scale-factor=0.65")
options.add_argument("high-dpi-support=0.65")

options.add_argument("--enable-javascript")
options.add_argument("--disable-web-security")


# options.add_argument('--disable-gpu')

# za proksi
# options.add_argument('--proxy-server=http://your_proxy_server:your_proxy_port')

# options.add_experimental_option("excludeSwitches", ["enable-automation"])
# options.add_experimental_option('useAutomationExtension', False)
prefs = {"profile.managed_default_content_settings.images": 2}
options.add_experimental_option("prefs", prefs)

driver = webdriver.Chrome(options=options, service=ChromeService(
ChromeDriverManager().install()))

driver.execute_cdp_cmd('Network.setUserAgentOverride', {"userAgent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"})

version = randint(10, 99)

stealth(driver,
    # user_agent = f'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.{version} (KHTML, like Gecko) Chrome/110.0.5481.105 Safari/537.36',
    user_agent= "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/119.0",
    languages=["en-US", "en"],
    vendor="Google Inc.",
    platform="Win32",
    webgl_vendor="Intel Inc.",
    renderer="Intel Iris OpenGL Engine",
    fix_hairline=True,
    )

url1 = 'https://www.very.co.uk/office-office-korey-knee-boot/1600912122.prd'
url2 = 'https://www.very.co.uk/ugg-tazz-platform-slipper-mustard-seed/1600880639.prd'
url3 = 'https://www.very.co.uk/nike-air-max-270-black/1600236946.prd'

driver.implicitly_wait(5)
time.sleep(2)
# driver.get("https://stackoverflow.com/questions/77215107/importerror-cannot-import-name-url-decode-from-werkzeug-urls")
driver.get(url2)
# driver.get('https://stockx.com/')
driver.implicitly_wait(5)
time.sleep(2)
WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR, "ul.ppOption li label")))

driver.save_screenshot("/home/timeforkicks2/web/development.timeforkicks.tk/public_html/src/python/pictures_selenium/sl.png")

# driver.implicitly_wait(2)
# out_of_stock = driver.find_element(By.CSS_SELECTOR, 'ul.ppOption li label')
# print(out_of_stock)
# indicator_text = out_of_stock.text
# if ("Out" in indicator_text or "out" in indicator_text or 'OUT' in indicator_text or 'of' in indicator_text or 'Of' in indicator_text or 'OF' in indicator_text):
#     print('Nema ni jedne velicine')
# else:
#     print('Ima dostupnih velicina')











