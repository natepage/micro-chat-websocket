<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Loader\LoaderInterface;

class MicroKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Gos\Bundle\WebSocketBundle\GosWebSocketBundle(),
            new Gos\Bundle\PubSubRouterBundle\GosPubSubRouterBundle(),
            new AppBundle\AppBundle(),
        );

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new CoreSphere\ConsoleBundle\CoreSphereConsoleBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $routes->mount('/', $routes->import('@CoreSphereConsoleBundle/Resources/config/routing.yml'));
        }

        $routes->mount('/', $routes->import('@AppBundle/Controller', 'annotation'));
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
        $loader->load(__DIR__.'/config/services.yml');
    }
}