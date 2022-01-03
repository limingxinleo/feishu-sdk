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
namespace Fan\Feishu;

use Fan\Feishu\Exception\RuntimeException;
use GuzzleHttp\Client;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractProvider implements ProviderInterface
{
    protected array $config;

    /**
     * @param $config = [
     *     'app_id' => '',
     *     'app_secret' => '',
     *     'http' => [
     *         'base_uri' => 'https://open.feishu.cn/',
     *         'timeout' => 2,
     *     ],
     * ]
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function client(): Client
    {
        return make(Client::class, [$this->config['http']]);
    }

    protected function handleResponse(ResponseInterface $response): array
    {
        $ret = Json::decode((string) $response->getBody());
        if ($ret['code'] !== 0) {
            throw new RuntimeException($ret['msg'] ?? 'http request failed.');
        }

        return $ret;
    }
}
