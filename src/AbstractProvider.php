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

namespace HyperfX\Feishu;

use GuzzleHttp\Client;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\Codec\Json;
use HyperfX\Feishu\Exception\RuntimeException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractProvider
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $container->get(ConfigInterface::class);
    }

    public function client(): Client
    {
        return make(Client::class, [
            $this->getGuzzleConfig(),
        ]);
    }

    protected function handleResponse(ResponseInterface $response): array
    {
        $ret = Json::decode($response->getBody()->getContents());
        if ($ret['code'] !== 0) {
            throw new RuntimeException('http request failed.');
        }

        return $ret;
    }

    protected function getGuzzleConfig(): array
    {
        return $this->config->get('feishu.guzzle.config', [
            'base_uri' => 'https://open.feishu.cn',
            'timeout' => 2,
        ]);
    }
}
