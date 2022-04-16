<?php

namespace Anysrv\RecaptchaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class AnysrvRecaptchaExtension
 * @package Anysrv\RecaptchaBundle\DependencyInjection
 */
class AnysrvRecaptchaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        foreach ($config as $key => $value) {
            $container->setParameter('anysrv_recaptcha.' . $key, $value);
        }

        $this->registerTemplate($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function registerTemplate(ContainerBuilder $container)
    {
        $container->setParameter('twig.form.resources', array_merge(
            $container->getParameter('twig.form.resources'),
            ['AnysrvRecaptchaBundle:Form:anysrv_recaptcha_widget.html.twig']
        ));
    }
}
