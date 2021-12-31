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
use Hyperf\Contract\ConfigInterface;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractProvider implements ProviderInterface
{
    protected ConfigInterface $config;

    protected HandlerStackFactory $factory;

    protected string $name = '';

    public function __construct(protected ContainerInterface $container)
    {
        $this->config = $container->get(ConfigInterface::class);
        $this->factory = $container->get(HandlerStackFactory::class);
    }

    public function client(): Client
    {
        $config = $this->config->get('feishu.guzzle.config', [
            'base_uri' => 'https://open.feishu.cn/',
            'timeout' => 2,
        ]);
        // No need change handler, because native curl hook is better.
        // $config['handler'] = $this->factory->get($this->getName());
        return make(Client::class, [$config]);
    }

    public function getName(): string
    {
        return $this->name;
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
