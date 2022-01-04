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

use Fan\Feishu\AccessTokenInterface;
use Fan\Feishu\Exception\RuntimeException;
use Fan\Feishu\Exception\TokenInvalidException;
use Fan\Feishu\ProviderInterface;
use GuzzleHttp;
use GuzzleHttp\RequestOptions;
use Hyperf\Utils\Codec\Json;
use Pimple\Container;
use Psr\Http\Message\ResponseInterface;

class Client implements ProviderInterface
{
    public function __construct(protected Container $pimple, protected array $config)
    {
    }

    public function client(?AccessTokenInterface $token = null): GuzzleHttp\Client
    {
        $config = $this->config;
        if ($token) {
            $config[RequestOptions::HEADERS]['Authorization'] = 'Bearer ' . $token->getToken();
        }

        return make(GuzzleHttp\Client::class, [$config]);
    }

    public function handleResponse(ResponseInterface $response): array
    {
        $ret = Json::decode((string) $response->getBody());
        if ($ret['code'] !== 0) {
            $code = (int) $ret['code'];
            if ($code >= 99991661 && $code <= 99991668) {
                throw new TokenInvalidException();
            }

            throw new RuntimeException($ret['msg'] ?? 'http request failed.', $ret['code']);
        }

        return $ret;
    }

    public static function getName(): string
    {
        return 'http';
    }
}
