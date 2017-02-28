<?php

namespace AppBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapNodeSubscriber implements EventSubscriberInterface
{

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param ObjectManager $manager
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, ObjectManager $manager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->manager = $manager;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'registerNodesPages',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function registerNodesPages(SitemapPopulateEvent $event)
    {
        $nodes = $this->manager->getRepository('AppBundle:Node')->findAll();
        foreach ($nodes as $node) {
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'single_node',
                        ['slug' => $node->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'nodes'
            );
        }

        foreach (range('a', 'z') as $letter) {
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'list_nodes',
                        ['slug' => strtoupper($letter)],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'alphabetical'
            );
        }
    }
}