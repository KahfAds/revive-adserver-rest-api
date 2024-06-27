<?php

use Slim\Factory\AppFactory;

require_once '../../../../init.php';
require_once MAX_PATH . '/lib/vendor/autoload.php';

require_once './errors/handler/CustomErrorHandler.php';
require_once './controllers/RestFrontController.php';
require_once './controllers/AnalyticsController.php';
require_once './AppContainer.php';

//check if restAPI properties exists in /var/<hostname>.conf.php file or not
if($GLOBALS['_MAX']['CONF']['webpath']['restAPI']){
    define('API_BASE_PATH', $GLOBALS['_MAX']['CONF']['webpath']['restAPI']);
}

if (!defined('API_BASE_PATH')) {
    define('API_BASE_PATH', '/adserver/www/api/v2/rest');
}

// Instantiate App
$container = AppContainer::getContainer();

$app = AppFactory::createFromContainer($container);
//Middleware
$app->addRoutingMiddleware();

$app->add($container->get('SessionTokenMiddleware'));
$app->add($container->get('JsonBodyParserMiddleware'));
$app->add($container->get('ContentTypeMiddleware'));

// Add error middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(new CustomErrorHandler($app, $app->getResponseFactory()));

// Set base path
$app->setBasePath(API_BASE_PATH);

/*routes*/
$app->get('/', RestFrontController::class . ':index');

//authentication
$app->post('/login', RestFrontController::class . ':login');
$app->post('/logout', RestFrontController::class . ':logout');

//agencies
$app->get('/agencies/{id}', RestFrontController::class . ':agencyDetails');
$app->get('/agencies/{id}/advertisers', RestFrontController::class . ':agencyAdvertisers');

$app->get('/agencies/{id}/statistics/daily', AnalyticsController::class . ':agencyDailyStatistics');
$app->get('/agencies/{id}/statistics/hourly', AnalyticsController::class . ':agencyHourlyStatistics');
$app->get('/agencies/{id}/statistics/campaign', AnalyticsController::class . ':agencyCampaignStatistics');
$app->get('/agencies/{id}/statistics/banner', AnalyticsController::class . ':agencyBannerStatistics');
$app->get('/agencies/{id}/statistics/zone', AnalyticsController::class . ':agencyZoneStatistics');
$app->get('/agencies/{id}/statistics/publisher', AnalyticsController::class . ':agencyPublisherStatistics');


//advertisers
$app->post('/advertisers', RestFrontController::class . ':addAdvertiser');
$app->get('/advertisers/{id}', RestFrontController::class . ':advertiserDetails');
$app->put('/advertisers/{id}', RestFrontController::class . ':updateAdvertiser');
$app->delete('/advertisers/{id}', RestFrontController::class . ':deleteAdvertiser');
$app->get('/advertisers/{id}/campaigns', RestFrontController::class . ':advertiserCampaigns');

$app->get('/advertisers/{id}/statistics/daily', AnalyticsController::class . ':advertiserDailyStatistics');
$app->get('/advertisers/{id}/statistics/hourly', AnalyticsController::class . ':advertiserHourlyStatistics');
$app->get('/advertisers/{id}/statistics/campaign', AnalyticsController::class . ':advertiserCampaignStatistics');
$app->get('/advertisers/{id}/statistics/banner', AnalyticsController::class . ':advertiserBannerStatistics');
$app->get('/advertisers/{id}/statistics/zone', AnalyticsController::class . ':advertiserZoneStatistics');
$app->get('/advertisers/{id}/statistics/publisher', AnalyticsController::class . ':advertiserPublisherStatistics');

//campaigns
$app->post('/campaigns', RestFrontController::class . ':addCampaign');
$app->put('/campaigns/{id}', RestFrontController::class . ':updateCampaign');
$app->delete('/campaigns/{id}', RestFrontController::class . ':deleteCampaign');
$app->get('/campaigns/{id}', RestFrontController::class . ':campaignDetails');
$app->get('/campaigns/{id}/banners', RestFrontController::class . ':campaignBanners');

$app->get('/campaigns/{id}/statistics/daily', AnalyticsController::class . ':campaignDailyStatistics');
$app->get('/campaigns/{id}/statistics/hourly', AnalyticsController::class . ':campaignHourlyStatistics');
$app->get('/campaigns/{id}/statistics/banner', AnalyticsController::class . ':campaignBannerStatistics');
$app->get('/campaigns/{id}/statistics/zone', AnalyticsController::class . ':campaignZoneStatistics');
$app->get('/campaigns/{id}/statistics/publisher', AnalyticsController::class . ':campaignPublisherStatistics');
$app->get('/campaigns/{id}/statistics/conversion', AnalyticsController::class . ':campaignConversionStatistics');

//banners
$app->post('/banners', RestFrontController::class . ':addBanner');
$app->put('/banners/{id}', RestFrontController::class . ':updateBanner');
$app->delete('/banners/{id}', RestFrontController::class . ':deleteBanner');
$app->get('/banners/{id}', RestFrontController::class . ':bannerDetails');
$app->get('/banners/{id}/targeting', RestFrontController::class . ':bannerTargeting');
$app->post('/banners/{id}/targeting', RestFrontController::class . ':addBannerTargeting');

$app->get('/banners/{id}/statistics/daily', AnalyticsController::class . ':bannerDailyStatistics');
$app->get('/banners/{id}/statistics/hourly', AnalyticsController::class . ':bannerHourlyStatistics');
$app->get('/banners/{id}/statistics/zone', AnalyticsController::class . ':bannerZoneStatistics');
$app->get('/banners/{id}/statistics/publisher', AnalyticsController::class . ':bannerPublisherStatistics');



//publishers
$app->get('/publishers/{id}', RestFrontController::class . ':publisherDetails');
$app->get('/publishers/{id}/zones', RestFrontController::class . ':publisherZones');

$app->get('/publishers/{id}/statistics/daily', AnalyticsController::class . ':publisherDailyStatistics');
$app->get('/publishers/{id}/statistics/hourly', AnalyticsController::class . ':publisherHourlyStatistics');
$app->get('/publishers/{id}/statistics/campaign', AnalyticsController::class . ':publisherCampaignStatistics');
$app->get('/publishers/{id}/statistics/banner', AnalyticsController::class . ':publisherBannerStatistics');
$app->get('/publishers/{id}/statistics/zone', AnalyticsController::class . ':publisherZoneStatistics');
$app->get('/publishers/{id}/statistics/advertiser', AnalyticsController::class . ':publisherAdvertiserStatistics');


//zones
$app->patch('/zones/{zone-id}/banners/{banner-id}/link', RestFrontController::class . ':linkBannerWithZone');
$app->patch('/zones/{zone-id}/banners/{banner-id}/unlink', RestFrontController::class . ':unlinkBannerFromZone');
$app->patch('/zones/{zone-id}/campaigns/{campaign-id}/link', RestFrontController::class . ':linkCampaignWithZone');
$app->patch('/zones/{zone-id}/campaigns/{campaign-id}/unlink', RestFrontController::class . ':unlinkCampaignFromZone');
/*routes ends*/

$app->run();
