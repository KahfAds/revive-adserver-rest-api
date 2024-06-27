<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RestUtils
{
    const SESSION_ID_KEY = 'sessionID';

    public static function prepareResponse(Response $response, array $payload, string $message): Response
    {
        $newPayload = array(
            'message' => $message,
            'data' => $payload,
            'timestamp' => time()
        );

        return RestUtils::responseBody($response, $newPayload);
    }

    public static function responseBody(Response $response, array $payload): Response
    {

        if (count($payload['data']) == 0) {
            $json = json_encode($payload, JSON_FORCE_OBJECT);
        } else {
            $json = json_encode($payload);
        }
        $response->getBody()->write($json);
        return $response;
    }

    public static function getSessionId(Request $request): string
    {
        return $request->getHeaderLine(RestUtils::SESSION_ID_KEY);
    }

    public static function getQueryParam(Request $request, string $paramName): ?string
    {
        return $request->getQueryParams()[$paramName];
    }
}
