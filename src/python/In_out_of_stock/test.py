from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions


# url = 'https://stackoverflow.com/questions/77215107/importerror-cannot-import-name-url-decode-from-werkzeug-urls'
# browser = webdriver.Firefox()
# browser.get(url)
# button = browser.find_element(By.CSS_SELECTOR, '#question-header div a')
# button.click()
# print(button)


#zbog svih velicina pokusava da selektuje klasu out of stock 
url = 'https://www.very.co.uk/office-office-korey-knee-boot/1600912122.prd'
browser = webdriver.Firefox()
browser.get(url)

# ovo da se implementira u mainu da bi mogao i velicine da proverava
brojac = 3 # mora da se implementira brojac zato sto su vrv indenticni elementi lise i onda ce index da hvata u uvek prvi na koji naidje 
# brojac ipak nece trebai mogu da idem li.text pa ce da nadje
za_upisivanje = {}
pokusaj = browser.find_elements(By.CSS_SELECTOR, 'ul.ppOption li label')
for li in pokusaj:
    klasa = li.get_attribute('class')
    if 'out' in klasa or 'Out' in klasa or 'OUT' in klasa:
        print(f"Nije dostupan broj: {li.text}")
        za_upisivanje[li.text] = 'Nedostupno'
    else:
        print(f"Dostupan broj: {li.text}")
        za_upisivanje[li.text] = 'Dostupno'
    brojac += 1

print(za_upisivanje)

# niz_pokusaj = browser.find_elements(By.CSS_SELECTOR, 'li')

