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

use Fan\Feishu\Exception\RuntimeException;
use Fan\Feishu\ProviderInterface;
use GuzzleHttp;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;

class Client implements ProviderInterface
{
    public function __construct(protected array $config)
    {
    }

    public function client(): GuzzleHttp\Client
    {
        return make(GuzzleHttp\Client::class, [$this->config]);
    }

    public function handleResponse(ResponseInterface $response): array
    {
        $ret = Json::decode((string) $response->getBody());
        if ($ret['code'] !== 0) {
            throw new RuntimeException($ret['msg'] ?? 'http request failed.');
        }

        return $ret;
    }

    public static function getName(): string
    {
        return 'http';
    }
}
