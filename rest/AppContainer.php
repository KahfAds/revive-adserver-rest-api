<?php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

require_once '../xmlrpc/LogonServiceImpl.php';
require_once '../xmlrpc/AgencyServiceImpl.php';
require_once '../xmlrpc/PublisherServiceImpl.php';
require_once '../xmlrpc/AdvertiserServiceImpl.php';
require_once '../xmlrpc/CampaignServiceImpl.php';
require_once '../xmlrpc/BannerServiceImpl.php';
require_once '../xmlrpc/ZoneServiceImpl.php';
require_once './middlewares/ContentTypeMiddleware.php';
require_once './middlewares/SessionTokenMiddleware.php';
require_once './middlewares/JsonBodyParserMiddleware.php';

class AppContainer
{
    
    public static function getContainer(): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->setDefinition('LoginService', new Definition(LogonServiceImpl::class));
        $containerBuilder->setDefinition('AgencyService', new Definition(AgencyServiceImpl::class));
        $containerBuilder->setDefinition('AdvertiserService', new Definition(AdvertiserServiceImpl::class));
        $containerBuilder->setDefinition('CampaignService', new Definition(CampaignServiceImpl::class));
        $containerBuilder->setDefinition('BannerService', new Definition(BannerServiceImpl::class));
        $containerBuilder->setDefinition('ZoneService', new Definition(ZoneServiceImpl::class));
        $containerBuilder->setDefinition('PublisherService', new Definition(PublisherServiceImpl::class));
        $containerBuilder->setDefinition('ContentTypeMiddleware', new Definition(ContentTypeMiddleware::class));
        $containerBuilder->setDefinition('JsonBodyParserMiddleware', new Definition(JsonBodyParserMiddleware::class));
        $containerBuilder->setDefinition('SessionTokenMiddleware', new Definition(SessionTokenMiddleware::class))
        ->addArgument(new Reference('LoginService'));

        $containerBuilder->setDefinition("RestFrontController", new Definition(RestFrontController::class))
            ->addArgument(new Reference('LoginService'))
            ->addArgument(new Reference('AgencyService'))
            ->addArgument(new Reference('AdvertiserService'))
            ->addArgument(new Reference('PublisherService'))
            ->addArgument(new Reference('CampaignService'))
            ->addArgument(new Reference('BannerService'))
            ->addArgument(new Reference('ZoneService'));

        $containerBuilder->setDefinition("AnalyticsController", new Definition(AnalyticsController::class))
            ->addArgument(new Reference('AgencyService'))
            ->addArgument(new Reference('AdvertiserService'))
            ->addArgument(new Reference('PublisherService'))
            ->addArgument(new Reference('CampaignService'))
            ->addArgument(new Reference('BannerService'))
            ->addArgument(new Reference('ZoneService'));

        return $containerBuilder;
    }
}
