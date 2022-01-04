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
namespace Fan\Feishu\AccessToken;

use Fan\Feishu\AccessTokenInterface;
use Fan\Feishu\Config\Config;
use Fan\Feishu\Http\Client;
use Fan\Feishu\ProviderInterface;
use GuzzleHttp\RequestOptions;

abstract class AccessToken implements AccessTokenInterface, ProviderInterface
{
    protected string $token = 't-89308966393ccd31a05edd2a2737233cf6f74b91';

    protected int $expireTime = 0;

    public function __construct(protected Config $config, protected Client $client)
    {
    }

    public function getToken(bool $refresh = false): string
    {
        if (! $this->isExpired() && ! $refresh) {
            return $this->token;
        }

        $response = $this->client->client()->post('open-apis/auth/v3/' . static::getName() . '/internal/', [
            RequestOptions::JSON => [
                'app_id' => $this->config->getAppId(),
                'app_secret' => $this->config->getAppSecret(),
            ],
        ]);

        $ret = $this->client->handleResponse($response);

        $this->expireTime = $ret['expire'] + time();

        return $this->token = $ret[static::getName()];
    }

    public function getId(): string
    {
        return $this->config->getAppId();
    }

    protected function isExpired(): bool
    {
        return $this->expireTime <= time() + 60;
    }
}
