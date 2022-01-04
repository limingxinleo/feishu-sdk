<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Fan\Feishu\Http;

use Fan\Feishu\AccessToken\TenantAccessToken;
use Fan\Feishu\AccessTokenInterface;
use GuzzleHttp\Middleware;
use Hyperf\Utils\Codec\Json;
use Pimple\Container;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RefreshTokenMiddleware
{
    public function __construct(private int $retries = 1)
    {
    }

    public function getMiddleware(AccessTokenInterface $token): callable
    {
        return Middleware::retry(function ($retries, RequestInterface $request, ResponseInterface $response = null) use ($token) {
            if ($response->getStatusCode() === 400 && $retries < $this->retries) {
                $data = Json::decode((string) $response->getBody());
                $code = $data['code'] ?? null;
                if ($code >= 99991661 && $code <= 99991668) {
                    $token->getToken(true);
                    return true;
                }
            }

            return false;
        }, function () {
            return 0;
        });
    }
}
