<?php

namespace Anysrv\RecaptchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Anysrv\RecaptchaBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('anysrv_recaptcha');

        $rootNode
            ->children()
                ->booleanNode('enabled')->defaultTrue()->end()
                ->scalarNode('sitekey')->isRequired()->end()
                ->scalarNode('secret')->isRequired()->end()
                ->arrayNode('options')
                    ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('theme')->defaultValue('light')->end()
                            ->scalarNode('type')->defaultValue('image')->end()
                            ->scalarNode('size')->defaultValue('normal')->end()
                            ->integerNode('tabindex')->defaultValue(0)->end()
                        ->end()
                ->end()
                ->scalarNode('overwrite_locale')->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }
}
