<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;

require_once './utils/RestUtils.php';

class RestFrontController
{
    private $agencyService;
    private $advertiserService;
    private $publisherService;
    private $campaignService;
    private $bannerService;
    private $zoneService;
    private $loginService;

    public function __construct(
        LogonServiceImpl $logonService,
        AgencyServiceImpl $agencyService,
        AdvertiserServiceImpl $advertiserService,
        PublisherServiceImpl $publisherService,
        CampaignServiceImpl $campaignService,
        BannerServiceImpl $bannerService,
        ZoneServiceImpl $zoneService
    ) {
        $this->loginService = $logonService;
        $this->agencyService = $agencyService;
        $this->advertiserService = $advertiserService;
        $this->publisherService = $publisherService;
        $this->campaignService = $campaignService;
        $this->bannerService = $bannerService;
        $this->zoneService = $zoneService;
    }

    public function index(Request $request, Response $response): Response
    {
        $payload = array('status' => 'UP');
        $response->getBody()->write(json_encode($payload));
        return $response;
    }

    /* Authentication APIs */
    public function login(Request $request, Response $response): Response
    {
        $sessionId = null;
        $payload = $request->getParsedBody();
        $username = $payload['username'];
        $password = $payload['password'];

        if ($username === null || $password === null) {
            throw new HttpBadRequestException($request, 'Username and password is required');
        }

        if (!$this->loginService->logon($username, $password, $sessionId)) {
            throw new HttpUnauthorizedException($request, 'Invalid username or password or ' . $this->loginService->getLastError());
        }

        $payload = array(
            'username' => $username,
            'sessionID' => $sessionId,
            'created_at' => time(),
        );

        return RestUtils::prepareResponse($response, $payload, 'Login');
    }

    public function logout(Request $request, Response $response): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        if (!$this->loginService->logoff($sessionId)) {
            throw new HttpInternalServerErrorException($request, 'Failed to logout due to ' . $this->loginService->getLastError());
        }
        $payload = array();

        return RestUtils::prepareResponse($response, $payload, 'Logout');
    }

    /* Agency APIs */
    public function agencyDetails(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $agencyId = $args['id'];

        $agencyInfo = new OA_Dll_AgencyInfo();
        if (!$this->agencyService->getAgency($sessionId, $agencyId, $agencyInfo)) {
            throw new HttpNotFoundException($request, 'Agency details does not exists id=' . $agencyId . ' ' . $this->agencyService->getLastError());
        }
        $payload = $agencyInfo->toArray();
        return RestUtils::prepareResponse($response, $payload, 'Agency details');
    }

    public function agencyAdvertisers(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $agencyId = $args['id'];
        $advertiserList = array();

        if (!$this->advertiserService->getAdvertiserListByAgencyId($sessionId, $agencyId, $advertiserList)) {
            throw new HttpInternalServerErrorException($request, 'Failed to fetch advertisers due to ' . $this->advertiserService->getLastError());
        }

        $payload = array(
            'agencyId' => $agencyId,
            'advertisers' => $advertiserList
        );

        return RestUtils::prepareResponse($response, $payload, 'Advertiser list');
    }

    /* Publisher APIs */
    public function publisherDetails(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $publisherId = $args['id'];
        $publisherInfo = new OA_Dll_PublisherInfo();
        if (!$this->publisherService->getPublisher($sessionId, $publisherId, $publisherInfo)) {
            throw new HttpNotFoundException($request, 'Publisher details not exists id=' . $publisherId . ' ' . $this->publisherService->getLastError());
        }
        $payload = $publisherInfo->toArray();
        return RestUtils::prepareResponse($response, $payload, 'Advertisement details');
    }

    public function publisherZones(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $publisherId = intval($args['id']);
        $zones = array();
        if (!$this->zoneService->getZoneListByPublisherId($sessionId, $publisherId, $zones)) {
            throw new HttpBadRequestException($request, 'Failed to fetch zones due to ' . $this->zoneService->getLastError());
        }
        $payload = array(
            'publisherId' => $publisherId,
            'zones' => $zones
        );
        return RestUtils::prepareResponse($response, $payload, 'Zones list');
    }

    /* Advertiser APIs */
    public function addAdvertiser(Request $request, Response $response): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $payload = $request->getParsedBody();

        $advertiserName = $payload['advertiserName'];
        if ($advertiserName == null) {
            throw new HttpBadRequestException($request, 'Advertiser name is required');
        }

        $advertiserInfo = new OA_Dll_AdvertiserInfo();
        $advertiserInfo->readDataFromArray($payload);

        if (!$this->advertiserService->addAdvertiser($sessionId, $advertiserInfo)) {
            throw new HttpInternalServerErrorException($request, 'Failed to add advertiser due to ' . $this->advertiserService->getLastError());
        }

        $payload = $advertiserInfo->toArray();
        return RestUtils::prepareResponse($response, $payload, 'Advertiser added successfully')->withStatus(201);
    }

    public function advertiserDetails(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $advertiserId = $args['id'];

        $advertiserInfo = new OA_Dll_AdvertiserInfo();

        if (!$this->advertiserService->getAdvertiser($sessionId, $advertiserId, $advertiserInfo)) {
            throw new HttpNotFoundException($request, 'Advertiser details not exists id=' . $advertiserId . ' ' . $this->advertiserService->getLastError());
        }

        $payload = $advertiserInfo->toArray();

        return RestUtils::prepareResponse($response, $payload, 'Advertisement details');
    }

    public function updateAdvertiser(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $advertiserId = intval($args['id']);
        $payload = $request->getParsedBody();
        $advertiserId = $payload['advertiserId'];
        if ($advertiserId == null) {
            throw new HttpBadRequestException($request, 'Advertiser id is required');
        }
        $advertiserName = $payload['advertiserName'];
        if ($advertiserName == null) {
            throw new HttpBadRequestException($request, 'Advertiser name is required');
        }

        $advertiserInfo = new OA_Dll_AdvertiserInfo();
        $advertiserInfo->readDataFromArray($payload);
        $advertiserInfo->advertiserId = $advertiserId;

        if (!$this->advertiserService->modifyAdvertiser($sessionId, $advertiserInfo)) {
            throw new HttpInternalServerErrorException($request, 'Failed to update advertiser details id=' . $advertiserId . ' ' . $this->advertiserService->getLastError());
        }

        $payload = array();

        return RestUtils::prepareResponse($response, $payload, 'Advertiser updated successfully');
    }

    public function deleteAdvertiser(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $advertiserId = $args['id'];
        if (!$this->advertiserService->deleteAdvertiser($sessionId, $advertiserId)) {
            throw new HttpInternalServerErrorException($request, 'Failed to delete advertiser due to ' . $this->advertiserService->getLastError());
        }

        $payload = array();
        return RestUtils::prepareResponse($response, $payload, 'Advertiser deleted successfully');
    }

    public function advertiserCampaigns(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $advertiserId = intval($args['id']);
        $campaignList = array();
        if (!$this->campaignService->getCampaignListByAdvertiserId($sessionId, $advertiserId, $campaignList)) {
            throw new HttpNotFoundException($request, 'Failed to fetch campaigns of advertiser id=' . $advertiserId . ' due to ' . $this->campaignService->getLastError());
        }
        $payload = array(
            'advertiserId' => $advertiserId,
            'campaigns' => $campaignList
        );
        return RestUtils::prepareResponse($response, $payload, 'Campaign details');
    }

    /* Campaigns APIs*/
    public function addCampaign(Request $request, Response $response): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $payload = $request->getParsedBody();
        $campaignInfo = new OA_Dll_CampaignInfo();
        $campaignInfo->readDataFromArray($payload);
        if ($campaignInfo->advertiserId == null) {
            throw new HttpBadRequestException($request, 'Advertiser Id is required');
        }
        if ($campaignInfo->campaignName == null) {
            throw new HttpBadRequestException($request, 'Campaign name is required');
        }
        if (!$this->campaignService->addCampaign($sessionId, $campaignInfo)) {
            throw new HttpInternalServerErrorException($request, 'Failed to add campaign due to ' . $this->campaignService->getLastError());
        }
        $payload = $campaignInfo->toArray();
        return RestUtils::prepareResponse($response, $payload, 'Campaign added successfully')->withStatus(201);
    }

    public function updateCampaign(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $payload = $request->getParsedBody();
        $campaignId = intval($args['id']);
        $campaignInfo = new OA_Dll_CampaignInfo();
        $campaignInfo->readDataFromArray($payload);
        $campaignInfo->campaignId = $campaignId;

        if ($campaignInfo->campaignId == null) {
            throw new HttpBadRequestException($request, 'Campaign Id is requried');
        }
        if ($campaignInfo->campaignName == null) {
            throw new HttpBadRequestException($request, 'Campaign name is required');
        }
        if (!$this->campaignService->modifyCampaign($sessionId, $campaignInfo)) {
            throw new HttpInternalServerErrorException($request, 'Failed to update campaign due to ' . $this->campaignService->getLastError());
        }
        $payload = array();
        return RestUtils::prepareResponse($response, $payload, 'Campaign updated successfully');
    }

    public function deleteCampaign(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $campaignId = intval($args['id']);

        if (!$this->campaignService->deleteCampaign($sessionId, $campaignId)) {
            throw new HttpInternalServerErrorException($request, 'Failed to delete campaign id=' . $campaignId . ' due to ' . $this->campaignService->getLastError());
        }
        $payload = array();
        return RestUtils::prepareResponse($response, $payload, 'Campaign deleted successfully');
    }

    public function campaignDetails(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $campaignId = intval($args['id']);
        $campaignInfo = new OA_Dll_CampaignInfo();
        if (!$this->campaignService->getCampaign($sessionId, $campaignId, $campaignInfo)) {
            throw new HttpNotFoundException($request, 'Failed to fetch campaign id=' . $campaignId . ' due to ' . $this->campaignService->getLastError());
        }
        $payload = $campaignInfo->toArray();
        return RestUtils::prepareResponse($response, $payload, 'Campaign details');
    }

    public function campaignBanners(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $campaignId = intval($args['id']);

        $bannerList = array();

        if (!$this->bannerService->getBannerListByCampaignId($sessionId, $campaignId, $bannerList)) {
            throw new HttpNotFoundException($request, 'Failed to fetch campaign banners due to ' . $this->bannerService->getLastError());
        }

        $payload = array(
            'campaignId' => $campaignId,
            'banners' => $bannerList
        );
        return RestUtils::prepareResponse($response, $payload, 'Campaign banners');
    }

    /* Banner APIs */
    public function addBanner(Request $request, Response $response): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $payload = $request->getParsedBody();

        $bannerInfo = new OA_Dll_BannerInfo();
        $bannerInfo->readDataFromArray($payload);

        if ($bannerInfo->bannerName == null) {
            throw new HttpBadRequestException($request, 'Banner name is required');
        }

        if ($bannerInfo->campaignId == null) {
            throw new HttpBadRequestException($request, 'Campaign Id is required');
        }

        if (!$this->bannerService->addBanner($sessionId, $bannerInfo)) {
            throw new HttpInternalServerErrorException($request, 'Failed to add banner due to ' . $this->bannerService->getLastError());
        }

        $payload = $bannerInfo->toArray();
        return RestUtils::prepareResponse($response, $payload, 'Banner added successfully')->withStatus(201);
    }

    public function updateBanner(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $payload = $request->getParsedBody();
        $bannerId = intval($args['id']);

        $bannerInfo = new OA_Dll_BannerInfo();
        $bannerInfo->readDataFromArray($payload);
        $bannerInfo->bannerId = $bannerId;

        if (!$this->bannerService->modifyBanner($sessionId, $bannerInfo)) {
            throw new HttpInternalServerErrorException($request, 'Failed to update banner due to' . $this->bannerService->getLastError());
        }

        $payload = $bannerInfo->toArray();
        return RestUtils::prepareResponse($response, $payload, 'Banner updated successfully');
    }

    public function deleteBanner(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $bannerId = intval($args['id']);

        if (!$this->bannerService->deleteBanner($sessionId, $bannerId)) {
            throw new HttpInternalServerErrorException($request, 'Failed to delete banner due to ' . $this->bannerService->getLastError());
        }
        $payload = array();
        return RestUtils::prepareResponse($response, $payload, 'Banner deleted successfully');
    }

    public function bannerDetails(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $bannerId = intval($args['id']);

        $bannerInfo = new OA_Dll_BannerInfo();

        if (!$this->bannerService->getBanner($sessionId, $bannerId, $bannerInfo)) {
            throw new HttpNotFoundException($request, 'Failed to fetch banner details due to ' . $this->bannerService->getLastError());
        }

        $payload = $bannerInfo->toArray();
        return RestUtils::prepareResponse($response, $payload, 'Banner details');
    }

    public function bannerTargeting(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $bannerId = intval($args['id']);
        $targeting = array();
        if (!$this->bannerService->getBannerTargeting($sessionId, $bannerId, $targeting)) {
            throw new HttpNotFoundException($request, 'Failed to fetch banner targetings due to ' . $this->bannerService->getLastError());
        }
        $payload = array(
            'bannerId' => $bannerId,
            'targeting' => $targeting
        );
        return RestUtils::prepareResponse($response, $payload, 'Banner Targeting');
    }

    public function addBannerTargeting(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $bannerId = intval($args['id']);
        $payload = $request->getParsedBody();

        $targeting = array();
        foreach ($payload as $executionOrder => $oTargeting) {
            $targetingInfo = new OA_Dll_TargetingInfo();
            $targetingInfo->readDataFromArray($oTargeting);
            $targeting[$executionOrder] = $targetingInfo;
        }

        if (!$this->bannerService->setBannerTargeting($sessionId, $bannerId, $targeting)) {
            throw new HttpBadRequestException($request, 'Failed to add delivery options due to ' . $this->bannerService->getLastError());
        }
        $payload = array(
            'bannerId' => $bannerId,
            'targetings' => $targeting
        );
        return RestUtils::prepareResponse($response, $payload, 'Add banner targeting');
    }

    /* Zone APIs */
    public function linkBannerWithZone(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $zoneId = intval($args['zone-id']);
        $bannerId = intval($args['banner-id']);
        if (!$this->zoneService->linkBanner($sessionId, $zoneId, $bannerId)) {
            throw new HttpBadRequestException($request, 'Failed to link banner with zone due to ' . $this->zoneService->getLastError());
        }
        $payload = array();
        return RestUtils::prepareResponse($response, $payload, 'Link banner with campaign');
    }

    public function unlinkBannerFromZone(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $zoneId = intval($args['zone-id']);
        $bannerId = intval($args['banner-id']);
        if (!$this->zoneService->unlinkBanner($sessionId, $zoneId, $bannerId)) {
            throw new HttpBadRequestException($request, 'Failed to unlink banner from zone due to ' . $this->zoneService->getLastError());
        }
        $payload = array();
        return RestUtils::prepareResponse($response, $payload, 'Unlink banner from campaign');
    }

    public function linkCampaignWithZone(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $zoneId = intval($args['zone-id']);
        $campaignId = intval($args['campaign-id']);
        if (!$this->zoneService->linkCampaign($sessionId, $zoneId, $campaignId)) {
            throw new HttpBadRequestException($request, 'Failed to link campaign with zone due to ' . $this->zoneService->getLastError());
        }
        $payload = array();
        return RestUtils::prepareResponse($response, $payload, 'Link campaign with zone');
    }

    public function unlinkCampaignFromZone(Request $request, Response $response, array $args): Response
    {
        $sessionId = RestUtils::getSessionId($request);
        $zoneId = intval($args['zone-id']);
        $campaignId = intval($args['campaign-id']);
        if (!$this->zoneService->unlinkCampaign($sessionId, $zoneId, $campaignId)) {
            throw new HttpBadRequestException($request, 'Failed to unlink campaign from zone due to ' . $this->zoneService->getLastError());
        }
        $payload = array();
        return RestUtils::prepareResponse($response, $payload, 'Unlink campaign from zone');
    }
}
