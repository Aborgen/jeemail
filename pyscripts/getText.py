# Python 3.5.2
import argparse
import json
import random
from selenium import webdriver
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import WebDriverWait
import sys
from time import sleep

# /**
#  	 * Utilizing Selenium, this method will attempt to assign
#    * the text found in the <span id="result"> to a variable.
#    * If unsuccessful, it will timeout and raise an exception.
#    * @return STRING
#    */
def get_title(url, driver):
    driver.get(url)
    assert "Random Word Generator" in driver.title
    try:
        WebDriverWait(driver, 10).until(EC.presence_of_element_located(
            (By.XPATH, '//*[@id="result" and text() != ""]')))
    except Exception as e:
        driver.close()
        raise Exception("Could not get text after waiting for 10 seconds")
    text = driver.find_element_by_id('result').text
    if(len(text) == 0):
        print("Trying again due to empty string")
        get_title(url, driver)
    else:
        assert len(text) > 0
        return text[:-1]

# /**
#  	 * Utilizing Selenium, this method will attempt to assign
#    * the text found in the <div id="text"> to a variable.
#    * Before this happens, it performs a series of actions
#    * in order for the page to get to the right state.
#    * @return STRING
#    */
def get_body(url, driver):
    driver.get(url)
    assert "Dummy Text Generator" in driver.title
    i = random.randint(2, 14)
    n = random.randint(0, 3)
    if(i == 14 and n == 0):
        i = 14
        n = 3
    driver.execute_script(
        "const form = document.getElementById('fnumwords');"
        "const form2 = document.getElementById('fnumparagraphs');"
        "form.options[{}].selected = true;"
        "form2.options[{}].selected = true;".format(i, n)
    )

    use_english = driver.find_element_by_id('fenglish')
    italics = driver.find_element_by_id('femph')
    submit = driver.find_element_by_name('fsubmit')

    # default_text = driver.find_element_by_id('text').text

    actions = ActionChains(driver)
    actions.click(use_english)
    actions.click(italics)
    actions.click(submit)
    actions.perform()
    sleep(.5);

    try:
        driver.find_element_by_id('text').text
    except Exception as e:
        print (e)
        print("Trying again due to missing text")
        get_body(url, driver)

    text = driver.find_element_by_id('text').text
    return text

# /**
#  	 * Utilizing Selenium, this method will attempt to decode the
#    * string returned from the url into JSON and will then
#    * return the necessary values.
#    * If unsuccessful, it will timeout and raise an exception.
#    * @return DICT
#    */
def get_user(url, driver):
    driver.get(url)
    try:
        WebDriverWait(driver, 10).until(EC.presence_of_element_located(
            (By.XPATH, '//div[@id="content"]')))
    except Exception as e:
        driver.close()
        raise Exception("Could not get JSON after waiting for 10 seconds")
    text = driver.find_element_by_xpath('//*[@id="content"]').text
    try:
        user = {}
        # Convert stringified JSON into JSON proper
        decoded = json.loads(text)
        for res in decoded['results']:
            user['name'] = res['name']['first'] + " " + res['name']['last'].lower()
            user['username'] = res['login'].pop('username', None).lower()
            user['email'] = res['email'].lower()
    except Exception as e:
        print ("JSON format error")
        print ("Exception: {}".format(str(e)))

    if(len(user) == 0):
        print("Trying again due to empty dictionary")
        get_user(url, driver)
    else:
        assert len(user) > 0
        return user

# /**
#  	 * This method constructs a list of dictionaries containing return
#    * values from other getter functions. This is where
#    * this .py file '--repeat's.
#    * @param INT
#    */
def get_request(i):
    driver = webdriver.Firefox()
    email_list = []
    while True:
        # title     = get_title('http://watchout4snakes.com/wo4snakes/'
        #                       'random/randomsentence', driver)
        # body      = get_body('http://www.dummytextgenerator.com', driver)
        author    = get_user('https://randomuser.me/api/', driver)
        # recipient = get_user('https://randomuser.me/api/', driver)
        email_details = dict(
            # title=title,
            # body=body,
            author=author
            # recipient=recipient
        )

        email_list.append(email_details)

        if i == 0:
            driver.close()
            return email_list

        i = i - 1

    sys.exit("something went wrong")

# /**
#  	 * The last step of this program. Dumps all gathered
#    * values into an array within a json file.
#    * @param LIST
#    * @param INT
# 	 */
def write_to_file(email_list, i):
    file_name = open('data.json','w+')
    json.dump(email_list, file_name, indent=4, sort_keys=True)
    file_name.close()
    if(i == 0):
        print ("Successfully created 1 new email!")
        sys.exit(0)
    else:
        print ("Successfully created {} new emails!".format(i))
        sys.exit(0)

# /**
#  	 * Method uses library argparse to determine the amount of times
#    * program is repeated, if provided.
#  	 * @return INT
# 	 */
def get_args():
        parser = argparse.ArgumentParser()
        parser.add_argument("--repeat", metavar="<number>",
                            help="followed by an integer, runs that many times (MAX 100)")
        args = parser.parse_args()
        repeat = args.repeat
        if repeat:
            max = 100
            min = 1
            try:
                i = int(repeat)
            except:
                sys.exit("Error: Expected --repeat <number>")

            if i < min:
                sys.exit("Error: Expected {} or more repeats".format(min))
            if i > 100:
                sys.exit("Error: No more than {} repeats are allowed".format(max))
            print("Repeating {} times!".format(repeat))
            return i
        else:
            return 0

def main():
    repeat_num = get_args()
    email_list = get_request(repeat_num)
    write_to_file(email_list, repeat_num)

if __name__ == '__main__':
    main()
