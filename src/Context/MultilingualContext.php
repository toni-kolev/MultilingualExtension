<?php

namespace kolev\MultilingualExtension\Context;

use Behat\Behat\Context\Context;
use Symfony\Component\Yaml\Yaml;
use Drupal\DrupalExtension\Context\DrupalContext;
use Behat\MinkExtension;
use Behat\Behat\Context\TranslatableContext;
use Behat\Mink\Element\Element;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * This is the file for Multilingual context for Drupal. The context is working based on specifications
 * in the profile. The user needs to provide the language of the site and the translation file.
 *
 * The user is free to provide either full "english" or shortened "en" prefix for new languages
 * but all translations should follow common pattern. For example if the site's language
 * is marked as "en" you should define your translations with "en".
 *
 * It is important to note that the English language is used as a base.
 *
 * This is very early version of the context so it probably has some bugs/issues and points to be improved.
 *
 * @author Toni Kolev <kolev@toni-kolev.com>
 * @skype k-o-l-e-v
 * @github https://github.com/byKolev
 */

class MultilingualContext extends RawMultilingualContext {

    /** Multilanguage implementation */

    // Declaring translations variable to store all translations.
    public $translations = array();

    // Declaring languages_iso_codes variable to store all languages ISO codes from json file.
    public $languages_iso_codes = array();


    // Parse the YAML translations to PHP array variable.
    public function parseTranslationFile() {
        $base_path = $this->getMinkParameter('files_path');
        $base_path = $base_path."/";
        $file_path = $base_path.$this->multilingual_parameters['translations'];
        $yaml = file_get_contents($file_path);
        $yaml_parse_array_check = Yaml::parse($yaml);
        if(is_array($yaml_parse_array_check)) {
            $this->translations = $yaml_parse_array_check;
        }
    }

    // Checks whether the translations file variable is set in the Behat profile and parses it to array.
    public function initializeMultilanguage() {
        if(isset($this->multilingual_parameters['translations'])) {
            $this->parseTranslationFile();
        }
        $this->parseLanguageCodes();
    }

    //This function parses the languages_iso_codes.json file to an array.
    public function parseLanguageCodes() {
        $languages_iso_codes_string = file_get_contents("vendor/kolev/multilingual-extension/src/Resources/languages_iso_codes.json");
        $this->languages_iso_codes = json_decode($languages_iso_codes_string, true);
    }

    /*
     * This function detects site's language based on URL. If no URL language is detected
     * the default_language is used.
     */

    public function languageDetection() {
        $current_url = $this->getSession()->getCurrentUrl();
        $base_url = $this->getMinkParameter('base_url');
        // Checks whether the last symbol in the base_url is /, if not it adds it
        if (substr($base_url, -1) != "/") {
            $base_url = $base_url."/";
        }
        $base_url_length = strlen($base_url);
        //Get the 2 symbols in current URL after the base_url when Clean URLs is enabled
        $clean_url_language_code = substr($current_url,$base_url_length,2);
        $not_clean_url_language_code = substr($current_url,$base_url_length+3,2);
        if(in_array($clean_url_language_code,$this->languages_iso_codes) && substr($current_url,$base_url_length+2,1) == "/") {
            return $clean_url_language_code;
        }
        else if (in_array($not_clean_url_language_code,$this->languages_iso_codes) && substr($current_url,$base_url_length+5,1) == "/"){
            return $not_clean_url_language_code;
        }
        else return $this->multilingual_parameters['default_language'];
    }

    /**
     * This function localizes the targeted string. It tries to find a definition of the provided text (in English)
     * in the translations file that is provided within the profile parameters. If it fails to find translation
     * for the requested language it falls back to English. If the string is not defined at all in the translations
     * file there will be an exception thrown.
     */

    public function localizeTarget($target) {
        $translations = $this->multilingual_parameters['translations'];
        if(isset($this->translations[$target][$this->multilingual_parameters['default_language']])){
            $target = $this->translations[$target][$this->languageDetection()];
            return $target;
        }
        elseif (isset($this->translations[$target])) {
            return $target;
        }
        else throw new \Exception ("The text '$target'' is not defined in '$translations' translation file.");
    }

    /**
     * This function localizes the field based on Drupal standards. default_language variable is used as a base.
     */

    public function localizeField($field) {
        $re = "/(?:[-])(".$this->multilingual_parameters['default_language'].")(?:[-])/";
        $language = "-".$this->languageDetection()."-";
        $field = preg_replace($re, $language,$field);
        return $field;
    }


    /**
     * Initialize the multilingual context before the scenario.
     * @BeforeScenario
     * @Given /^I initialize multilingual context/
     */
    public function iInitializeMultilingualContext() {
        $this->initializeMultilanguage();
    }

    /**
     *
     * @Given /^I follow localized "(?P<link>(?:[^"]|\\")*)"/
     */
    public function iFollowLocalized($target) {

        $pos = strpos($target, '-en-');

        if ($pos === false) {
            $target = $this->localizeTarget($target);
        } else {
            $target = $this->localizeField($target);
        }
        
        $this->getSession()->getPage()->clickLink($target);
    }

    /**
     *
     * @Given /^I follow second localized "(?P<link>(?:[^"]|\\")*)"/
     */
    public function iFollowLocalizedSecond($target) {
        $target = $this->localizeTarget($target);
        $this->getSession()->getPage()->clickLink($target);
    }

    /**
     * Click on some text.
     *
     * @When /^I click on the localized text "([^"]*)"$/
     */

    public function iClickOnTheLocalizedText($target) {
        $target = $this->localizeTarget($target);
        $this->iClickOnTheText($target);
    }

    /**
     * Checks, that page contains specified text in input
     *
     * @Then /^(?:|I )should see localized value "(?P<text>(?:[^"]|\\")*)" in input "([^"]*)"$/
     */

    public function iShouldSeeLocalizedValueInInput($value, $input) {
        $value = $this->localizeTarget($value);
        $this->assertValueInInput($value, $input);
    }

    /**
     * Checks, that page contains specified text
     *
     * @Then /^(?:|I )should see localized "(?P<text>(?:[^"]|\\")*)"$/
     */

    public function iShouldSeeLocalized($target) {
        $target = $this->localizeTarget($target);
        $this->assertSession()->pageTextContains($target);
    }

    /**
     * Checks, that page doesn't contain specified text
     *
     * @Then /^(?:|I )should not see localized "(?P<text>(?:[^"]|\\")*)"$/
     */
    public function iShouldNotSeeLocalized($target)
    {
        $target = $this->localizeTarget($target);
        $this->assertSession()->pageTextNotContains($target);
    }

    /**
     * Waiting for text to appear on a page with certain execution time
     *
     * @When /^I wait for localized text "([^"]*)" to appear with max time "([^"]+)"(?: seconds)?$/
     */
    public function iWaitForLocalizedTextToAppearWithMaxTime($target, $maxExecutionTime){
        $target = $this->localizeTarget($target);
        $this->iWaitForTextToAppearWithMaxTime($target, $maxExecutionTime);
    }

    /**
     * Fills in form field with specified id|name|label|value
     * Example: When I fill in "username" with: "bwayne"
     * Example: And I fill in "bwayne" for "username"
     *
     * @When /^(?:|I )fill in localized "(?P<field>(?:[^"]|\\")*)" with "(?P<value>(?:[^"]|\\")*)"$/
     * @When /^(?:|I )fill in localized "(?P<field>(?:[^"]|\\")*)" with:$/
     * @When /^(?:|I )fill in localized "(?P<value>(?:[^"]|\\")*)" for "(?P<field>(?:[^"]|\\")*)"$/
     */
    public function fillLocalizedField($field, $value)
    {
        $field = $this->localizeField($field);
        $this->getSession()->getPage()->fillField($field, $value);
    }

    /**
     * @Given I click localized :link in the :rowText row
     * @Then I (should )see the localized :link in the :rowText row
     */
    public function assertLocalizedClickInTableRow($link, $rowText){
        $link = $this->localizeTarget($link);
        $page = $this->getSession()->getPage();
        if ($link_element = $this->getTableRow($page, $rowText)->findLink($link)) {
            // Click the link and return.
            $link_element->click();
            return;
        }
        throw new \Exception(sprintf('Found a row containing "%s", but no "%s" link on the page %s', $rowText, $link, $this->getSession()->getCurrentUrl()));
    }

    public function getTableRow(Element $element, $search) {
        $rows = $element->findAll('css', 'tr');
        if (empty($rows)) {
            throw new \Exception(sprintf('No rows found on the page %s', $this->getSession()->getCurrentUrl()));
        }
        foreach ($rows as $row) {
            if (strpos($row->getText(), $search) !== FALSE) {
                return $row;
            }
        }
        throw new \Exception(sprintf('Failed to find a row containing "%s" on the page %s', $search, $this->getSession()->getCurrentUrl()));
    }

    /**
     * Click on text in specified region
     *
     * @When /^I click on the localized text "([^"]*)" in the "(?P<region>[^"]*)"(?:| region)$/
     */
    public function iClickOnTheLocalizedTextInRegion($text, $region){
        $text = $this->localizeTarget($text);
        $this->iClickOnTheTextInRegion($text, $region);
    }

    /**
     * Choose certain option from given selector
     *
     * @When I select localized :option from chosen :selector
     */
    public function lselectLocalizedOptionWithJavascript($selector, $option) {
        $localizedOption = $this->localizeTarget($option);
        $this->selectOptionWithJavascript($localizedOption, $selector);
    }

    /**
     * @When I select the localized radio button :label with the id :id
     * @When I select the localized radio button :label
     *
     */
    public function assertSelectLocalizedRadioById($label, $id = '') {
        $label = $this->localizeTarget($label);
        $this->assertSelectRadioById($label, $id);
    }
}
