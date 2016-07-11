<?php
/**
 * @author Toni Kolev, <kolev@toni-kolev.com>
 */
namespace kolev\MultilingualExtension\Context;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Class RawMultilingualContext.
 *
 * @package kolev\MultilingualExtension\Context
 */
class RawMultilingualContext extends RawMinkContext implements MultilingualContextInterface
{

    /**
     * Parameters of MultilingualExtension.
     *
     * @var array
     */
    public $multilingual_parameters = [];

    /**
     * {@inheritdoc}
     */
    public function setMultilingualParameters(array $parameters)
    {
        if (empty($this->multilingual_parameters)) {
            $this->multilingual_parameters = $parameters;
        }
    }

    /**
     * @param string $name
     *   The name of parameter from behat.yml.
     *
     * @return mixed
     */
    protected function getMultilingualParameter($name)
    {
        return isset($this->multilingual_parameters[$name]) ? $this->multilingual_parameters[$name] : false;
    }

    /**
     *  RAW definitions of simple functions to  be used in MultilingualContext
     */

    public function iClickOnTheText($text) {

        $session = $this->getSession();
        $element = $session->getPage()->find(
            'xpath',
            $session->getSelectorsHandler()->selectorToXpath('xpath', '//*[contains(text(),"' . $text . '")]'));

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }

        $element->click();
    }

    public function assertValueInInput($value, $input) {

        if (substr($input,0,1) != "#") {
            $input = "#" . $input;
        }
        $session = $this->getSession();
        $element = $session->getPage()->find('css', $input);
        if(isset($element)) {
            $text = $element->getValue();
        }
        else {
            throw new \Exception(sprintf("Element is null"));
        }

        if($text === $value) {
            return true;
        }
        else {
            throw new \Exception(sprintf('Value of input : "%s" does not match the text "%s"', $text, $value));
        }
    }

    public function iWaitForTextToAppearWithMaxTime($text, $maxExecutionTime) {

        $isTextFound = false;

        for ($i = 0; $i < $maxExecutionTime; $i++) {
            try {
                $this->iShouldSeeInTheSourceOfThePage($text);
                $isTextFound = true;
                break;
            }
            catch (\Exception $e) {
                sleep(1);
            }
        }

        if (!$isTextFound) {
            throw new \Exception("'$text' didn't appear on the page for $maxExecutionTime seconds");
        }
    }

    public function iShouldSeeInTheSourceOfThePage($text) {

        $html = $this->getSession()->getDriver()->getContent();
        $text = $this->validateTextForSearchInSource($text);
        $regex = '/' . $text . '/';

        preg_match($regex, $html, $results);

        if ($results == null) {
            throw new \Exception('The searched text ' . $text . ' was not found in the source of the page.');
        }

        return true;
    }

    public function validateTextForSearchInSource($text) {

        $text = preg_replace("/(\\.)/", '\\\\.', $text);
        $text = preg_replace("/(\/)/", '\\/', $text);
        $text = preg_replace("/(\\?)/", '\\\\\?', $text);

        return $text;
    }

    public function iClickOnTheTextInRegion($text, $region) {

        $session = $this->getSession();
        $element = $session->getPage()->find('region', $region)->find('xpath', $session->getSelectorsHandler()->selectorToXpath('xpath',
            '//*[contains(text(),"' . $text . '")]'));
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }

        $element->click();
    }

    public function selectOptionWithJavascript($text, $region) {

        $session = $this->getSession();
        $element = $session->getPage()->find('region', $region)->find('xpath', $session->getSelectorsHandler()->selectorToXpath('xpath',
            '//*[contains(text(),"' . $text . '")]'));

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Cannot find text: "%s"', $text));
        }

        $element->click();
    }

    public function assertSelectRadioById($label, $id = '') {
        $element = $this->getSession()->getPage();
        $radiobutton = $id ? $element->findById($id) : $element->find('named', array('radio', $this->getSession()->getSelectorsHandler()->xpathLiteral($label)));
        if ($radiobutton === NULL) {
            throw new \Exception(sprintf('The radio button with "%s" was not found on the page %s', $id ? $id : $label, $this->getSession()->getCurrentUrl()));
        }
        $value = $radiobutton->getAttribute('value');
        $labelonpage = $radiobutton->getParent()->getText();
        if ($label != $labelonpage) {
            throw new \Exception(sprintf("Button with id '%s' has label '%s' instead of '%s' on the page %s", $id, $labelonpage, $label, $this->getSession()->getCurrentUrl()));
        }
        $radiobutton->selectOption($value, FALSE);
    }

}
