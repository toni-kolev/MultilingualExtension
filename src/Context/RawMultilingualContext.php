<?php
/**
 * @author Toni Kolev, <kolev@toni-kolev.com>
 */
namespace kolev\MultilingualExtension\Context;

/**
 * Class RawMultilingualContext.
 *
 * @package Behat\MultilingualExtension\Context
 */
class RawMultilingualContext implements MultilingualContextInterface
{

    /**
     * Parameters of MultilingualExtension.
     *
     * @var array
     */
    private $parameters = [];

    /**
     * {@inheritdoc}
     */
    public function setMultilingualParameters(array $parameters)
    {
        if (empty($this->parameters)) {
            $this->parameters = $parameters;
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
        return isset($this->parameters[$name]) ? $this->parameters[$name] : false;
    }

}
