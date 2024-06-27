<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once './utils/RestUtils.php';

/* Analytics APIs */
class AnalyticsController
{

    private $agencyService;
    private $advertiserService;
    private $publisherService;
    private $campaignService;
    private $bannerService;
    private $zoneService;

    public function __construct(
        AgencyServiceImpl $agencyService,
        AdvertiserServiceImpl $advertiserService,
        PublisherServiceImpl $publisherService,
        CampaignServiceImpl $campaignService,
        BannerServiceImpl $bannerService,
        ZoneServiceImpl $zoneService
    )
    {
        $this->agencyService = $agencyService;
        $this->advertiserService = $advertiserService;
        $this->publisherService = $publisherService;
        $this->campaignService = $campaignService;
        $this->bannerService = $bannerService;
        $this->zoneService = $zoneService;
    }

    public function agencyDailyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $agencyId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->agencyService->getAgencyDailyStatistics(
            $sessionId,
            $agencyId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'agency_id' => $agencyId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Agency Daily Statistics');
    }

    public function agencyHourlyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $agencyId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->agencyService->getAgencyHourlyStatistics(
            $sessionId,
            $agencyId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'agency_id' => $agencyId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Agency Hourly Statistics');
    }

    public function agencyCampaignStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $agencyId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        // $data = array();
        $this->agencyService->getAgencyCampaignStatistics(
            $sessionId,
            $agencyId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'agency_id' => $agencyId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Agency Campaign Statistics');
    }

    public function agencyBannerStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $agencyId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);

        $this->agencyService->getAgencyBannerStatistics(
            $sessionId,
            $agencyId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'agency_id' => $agencyId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Agency Banner Statistics');
    }

    public function agencyPublisherStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $agencyId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->agencyService->getAgencyPublisherStatistics(
            $sessionId,
            $agencyId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'agency_id' => $agencyId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Agency Publisher Statistics');
    }

    public function agencyZoneStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $agencyId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->agencyService->getAgencyZoneStatistics(
            $sessionId,
            $agencyId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'agency_id' => $agencyId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Agency Zone Statistics');
    }

    /* Advertiser statistics */
    public function advertiserDailyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $advertiserId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->advertiserService->getAdvertiserDailyStatistics(
            $sessionId,
            $advertiserId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'advertiser_id' => $advertiserId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Advertiser Daily Statistics');
    }

    public function advertiserHourlyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $advertiserId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->advertiserService->getAdvertiserHourlyStatistics(
            $sessionId,
            $advertiserId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'advertiser_id' => $advertiserId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Advertiser Hourly Statistics');
    }

    public function advertiserCampaignStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $advertiserId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $this->advertiserService->getAdvertiserCampaignStatistics(
            $sessionId,
            $advertiserId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'advertiser_id' => $advertiserId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Advertiser Campaign Statistics');
    }

    public function advertiserBannerStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $advertiserId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->advertiserService->getAdvertiserBannerStatistics(
            $sessionId,
            $advertiserId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'advertiser_id' => $advertiserId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Advertiser Banner Statistics');
    }

    public function advertiserPublisherStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $advertiserId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->advertiserService->getAdvertiserPublisherStatistics(
            $sessionId,
            $advertiserId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'advertiser_id' => $advertiserId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Advertiser Publisher Statistics');
    }

    public function advertiserZoneStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $advertiserId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->advertiserService->getAdvertiserZoneStatistics(
            $sessionId,
            $advertiserId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'advertiser_id' => $advertiserId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Advertiser Zone Statistics');
    }

    /* Publisher statistics */
    public function publisherDailyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $publisherId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->publisherService->getPublisherDailyStatistics(
            $sessionId,
            $publisherId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'publisher_id' => $publisherId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Publisher Daily Statistics');
    }

    public function publisherHourlyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $publisherId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->publisherService->getPublisherHourlyStatistics(
            $sessionId,
            $publisherId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'publisher_id' => $publisherId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Publisher Hourly Statistics');
    }

    public function publisherAdvertiserStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $publisherId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->publisherService->getPublisherAdvertiserStatistics(
            $sessionId,
            $publisherId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'publisher_id' => $publisherId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Publisher Advertiser Statistics');
    }

    public function publisherZoneStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $publisherId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->publisherService->getPublisherZoneStatistics(
            $sessionId,
            $publisherId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'publisher_id' => $publisherId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Publisher Zone Statistics');
    }

    public function publisherCampaignStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $publisherId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);

        $this->publisherService->getPublisherCampaignStatistics(
            $sessionId,
            $publisherId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'publisher_id' => $publisherId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Publisher Campaign Statistics');
    }

    public function publisherBannerStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $publisherId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->publisherService->getPublisherBannerStatistics(
            $sessionId,
            $publisherId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'publisher_id' => $publisherId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Publisher Banner Statistics');
    }


    /* Campaign statistics */
    public function campaignDailyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $campaignId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->campaignService->getCampaignDailyStatistics(
            $sessionId,
            $campaignId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'campaign_id' => $campaignId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Campaign Daily Statistics');
    }

    public function campaignHourlyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $campaignId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->campaignService->getCampaignHourlyStatistics(
            $sessionId,
            $campaignId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'campaign_id' => $campaignId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Campaign Hourly Statistics');
    }

    public function campaignPublisherStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $campaignId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->campaignService->getCampaignPublisherStatistics(
            $sessionId,
            $campaignId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'campaign_id' => $campaignId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Campaign Publisher Statistics');
    }

    public function campaignBannerStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $campaignId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->campaignService->getCampaignBannerStatistics(
            $sessionId,
            $campaignId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'campaign_id' => $campaignId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Campaign Banner Statistics');
    }

    public function campaignZoneStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $campaignId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->campaignService->getCampaignZoneStatistics(
            $sessionId,
            $campaignId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'campaign_id' => $campaignId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Campaign Zone Statistics');
    }

    public function campaignConversionStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $campaignId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->campaignService->getCampaignConversionStatistics(
            $sessionId,
            $campaignId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $data = $recordSet;
        $payload = array(
            'campaign_id' => $campaignId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Campaign Conversion Statistics');
    }


    /* Banner statistics */
    public function bannerDailyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $bannerId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->bannerService->getBannerDailyStatistics(
            $sessionId,
            $bannerId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'banner_id' => $bannerId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Banner Daily Statistics');
    }

    public function bannerHourlyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $bannerId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->bannerService->getBannerHourlyStatistics(
            $sessionId,
            $bannerId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'banner_id' => $bannerId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Banner Hourly Statistics');
    }

    public function bannerPublisherStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $bannerId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->bannerService->getBannerPublisherStatistics(
            $sessionId,
            $bannerId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'banner_id' => $bannerId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Banner Publisher Statistics');
    }

    public function bannerZoneStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $bannerId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->bannerService->getBannerZoneStatistics(
            $sessionId,
            $bannerId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'banner_id' => $bannerId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Banner Zone Statistics');
    }

    /* Zone statistics */
    public function zoneDailyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $zoneId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->zoneService->getZoneDailyStatistics(
            $sessionId,
            $zoneId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'zone_id' => $zoneId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Zone Daily Statistics');
    }

    public function zoneHourlyStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $zoneId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        $data = array();
        $this->zoneService->getZoneHourlyStatistics(
            $sessionId,
            $zoneId,
            $oStartDate,
            $oEndDate,
            true,
            $data
        );

        $payload = array(
            'zone_id' => $zoneId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Zone Hourly Statistics');
    }

    public function zoneAdvertiserStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $zoneId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->zoneService->getZoneAdvertiserStatistics(
            $sessionId,
            $zoneId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'zone_id' => $zoneId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Zone Advertiser Statistics');
    }

    public function zoneCampaignStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $zoneId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->zoneService->getZoneCampaignStatistics(
            $sessionId,
            $zoneId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'zone_id' => $zoneId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Zone Campaign Statistics');
    }

    public function zoneBannerStatistics(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $zoneId = $args['id'];
        $startDate = RestUtils::getQueryParam($request, 'start-date');
        $endDate = RestUtils::getQueryParam($request, 'end-date');
        $oStartDate = new Date($startDate);
        $oEndDate = new Date($endDate);
        
        $this->zoneService->getZoneBannerStatistics(
            $sessionId,
            $zoneId,
            $oStartDate,
            $oEndDate,
            true,
            $recordSet
        );
        $recordSet->find();
        $data = $recordSet->getAll();

        $payload = array(
            'zone_id' => $zoneId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'statistics' => $data
        );
        return RestUtils::prepareResponse($response, $payload, 'Zone Banner Statistics');
    }
}
