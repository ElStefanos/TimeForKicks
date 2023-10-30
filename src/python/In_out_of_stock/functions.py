from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions

# Za pretragu bez otvaranja prozora
from selenium.webdriver.firefox.options import Options


def send_message():
    print("Sending message")


def catch_size_for_very(browser):
    dict_velicina = {}
    velicine = browser.find_elements(By.CSS_SELECTOR, 'ul.ppOption li label')
    for label in velicine:
        klasa = label.get_attribute('class')
        if 'out' in klasa or 'Out' in klasa or 'OUT' in klasa:
            dict_velicina[label.text] = 'Nedostupan'
        else:
            dict_velicina[label.text] = 'Dostupan'
    return dict_velicina


#prebaceno iz glavne f-je u u parametar pa se zve iz drugog fajla
url = 'https://www.very.co.uk/ugg-tazz-platform-slipper-mustard-seed/1600880639.prd'
url = 'https://www.very.co.uk/office-office-korey-knee-boot/1600912122.prd'


def very_co_uk_with_sizes_main_f(url):
    options = Options()
    # options.add_argument('--no-sandbox')
    options.add_argument("--headless")
    # options.add_argument('--disable-dev-shm-usage')

    browser = webdriver.Chrome('./chromedriver', options=options)
    browser.get(url)
    content = browser.page_source
    # dict_velicina = {}
    try:
        out_of_stock = browser.find_element(By.CSS_SELECTOR, 'span.indicator')
        indicator_text = out_of_stock.text
        if ("Out" in indicator_text or "out" in indicator_text or 'OUT' in indicator_text or 'of' in indicator_text or 'Of' in indicator_text or 'OF' in indicator_text):
            print('Nema ni jedne velicine')
            return None # Ako je potpuno out of stock vraca none
        else:
            send_message()
            return catch_size_for_very(browser=browser)

    except:
        send_message()
        return catch_size_for_very(browser=browser)


if __name__ == '__main__':
    dict_velicine = very_co_uk_with_sizes_main_f(url=url)
    print(dict_velicine)













