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

}
