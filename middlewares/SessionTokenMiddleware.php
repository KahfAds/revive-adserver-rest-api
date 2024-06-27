<?php

use Slim\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


class SessionTokenMiddleware
{

    private $loginService;

    public function __construct(LogonServiceImpl $loginService)
    {
        $this->loginService = $loginService;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $path = $request->getUri()->getPath();
        $lastPart = $this->parseUrl($path);
        if (!($lastPart === 'rest' || $lastPart === 'login') && !$request->hasHeader(RestUtils::SESSION_ID_KEY)) {
            throw new HttpUnauthorizedException($request, "Session is invalid");
        } else if ($lastPart === 'rest' || $lastPart === 'login') {
            return $handler->handle($request);
        }
        $sessionID = $request->getHeaderLine(RestUtils::SESSION_ID_KEY);
        if (!$this->loginService->verifySession($sessionID)) {
            throw new HttpUnauthorizedException($request, 'Authentication failed due to ' . $this->loginService->getLastError());
        }
        return $handler->handle($request);
    }

    function parseUrl(string $url): string
    {
        // Handle empty URL gracefully
        if (empty($url)) {
            return false;
        }

        // Remove trailing slash
        $url = rtrim($url, '/');

        // Split URL into parts
        $parts = explode('/', $url);

        // Ensure parts exist
        if (empty($parts)) {
            return false;
        }

        // Get the last path (case-insensitive comparison)
        $lastPart = strtolower(end($parts));

        // Compare with target paths
        return $lastPart;
    }
}
