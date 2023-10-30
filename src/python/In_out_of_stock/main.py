from functions import *


url1 = 'https://www.very.co.uk/office-office-korey-knee-boot/1600912122.prd'
url2 = 'https://www.very.co.uk/ugg-tazz-platform-slipper-mustard-seed/1600880639.prd'
url3 = 'https://www.very.co.uk/nike-air-max-270-black/1600236946.prd'

dictionary = very_co_uk_with_sizes_main_f(url=url1)
print(dictionary)

dictionary = very_co_uk_with_sizes_main_f(url=url2)
print(dictionary)

dictionary = very_co_uk_with_sizes_main_f(url=url3)
print(dictionary)