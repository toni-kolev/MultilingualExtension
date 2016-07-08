<?php
/**
 * @author Toni Kolev, <kolev@toni-kolev.com>
 */
namespace kolev\MultilingualExtension\ServiceContainer;

use Behat\EnvironmentLoader;
use Behat\Testwork\ServiceContainer\Extension;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class MultilingualExtension.
 *
 * @package Behat\MultilingualExtension\ServiceContainer
 */
class MultilingualExtension implements Extension
{
    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'multilingual';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new EnvironmentLoader($this, $container, $config);
        $loader->load();
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $config = $builder->children();

        foreach (['language', 'translations'] as $param) {
            $config->arrayNode($param)
                ->defaultValue([])
                ->prototype('scalar')
                ->end();
        }

        $config->end();
    }
}