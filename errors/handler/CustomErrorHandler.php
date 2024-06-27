<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Exception\HttpException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Exception\HttpInternalServerErrorException;
use Psr\Http\Message\ResponseFactoryInterface;
use Throwable;

class CustomErrorHandler
{
    private $app;
    private $responseFactory;

    public function __construct(App $app, ResponseFactoryInterface $responseFactory)
    {
        $this->app = $app;
        $this->responseFactory = $responseFactory;
    }

    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails,
        ?LoggerInterface $logger = null
    ): ResponseInterface {
        $path = $request->getUri()->getPath();

        if ($logger && $logErrors) {
            $logger->error($exception->getMessage(), [
                'path' => $path,
                'exception' => $exception,
            ]);
        }

        $statusCode = 500;
        $message = 'Internal Server Error';
        $details = '';
        if ($exception instanceof HttpNotFoundException) {
            $statusCode = 404;
            $message = 'Not Found';
            $details = $exception->getMessage();
        } elseif ($exception instanceof HttpUnauthorizedException) {
            $statusCode = 401;
            $message = 'Unauthorized';
            $details = $exception->getMessage();
        } else if ($exception instanceof HttpInternalServerErrorException) {
            $message = $exception->getMessage();
        } elseif ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $message = $exception->getMessage();
        } else {
            $details = $exception->getCode() . ' - ' . $exception->getMessage(); //. ' - ' . $exception->getTraceAsString();
        }

        $payload = [
            'status' => $statusCode,
            'message' => $message,
            'details' => $details,
            'path' => $path,
        ];

        $response = $this->responseFactory->createResponse();
        $response->getBody()->write(json_encode($payload, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }
}
