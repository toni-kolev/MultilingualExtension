<?php
/**
 * @author Toni Kolev, <kolev@toni-kolev.com>
 */
namespace kolev\MultilingualExtension\Context;

use Behat\Behat\Context\Context;

/**
 * Interface MultilingualContextInterface.
 *
 * @package Behat\MultilingualExtension\Context
 */
interface MultilingualContextInterface extends Context
{
    /**
     * Set parameters from behat.yml.
     *
     * @param array $parameters
     *   An array of parameters from configuration file.
     */
    public function setMultilingualParameters(array $parameters);
}
