<?php
namespace ChubProduction\InterkassaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('interkassa');

	    $rootNode
		    ->children()
	            ->arrayNode('connections')
				    ->isRequired()
				    ->requiresAtLeastOneElement()
	                ->useAttributeAsKey('name')
	                ->prototype('array')
	                    ->children()
						    ->scalarNode('shop_id')
						        ->isRequired()
						        ->cannotBeEmpty()
	                        ->end()
						    ->scalarNode('secret_key')
						        ->isRequired()
						        ->cannotBeEmpty()
						    ->end()
						    ->scalarNode('submit_url')
							    ->defaultValue('https://www.interkassa.com/lib/payment.php')
						    ->end()
						    ->scalarNode('success_url')
						        ->defaultValue('/')
						    ->end()
						    ->scalarNode('fail_url')
							    ->defaultValue('/')
						    ->end()
	                    ->end()
	                ->end()
	            ->end()
	        ->end();

        return $treeBuilder;
    }
}
