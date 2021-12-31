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

use Fan\Feishu\AbstractProvider;
use Fan\Feishu\AccessTokenInterface;
use GuzzleHttp\RequestOptions;
use Psr\Container\ContainerInterface;

abstract class AccessToken extends AbstractProvider implements AccessTokenInterface
{
    protected string $name = 'tenant_access_token';

    protected string $token = '';

    protected int $expireTime = 0;

    public function __construct(ContainerInterface $container, protected string $id, protected string $secret)
    {
        parent::__construct($container);
    }

    /**
     * 获取 TenantAccessToken.
     */
    public function getToken(bool $refresh = false): string
    {
        if (! $this->isExpired() && ! $refresh) {
            return $this->token;
        }

        $response = $this->client()->post('open-apis/auth/v3/' . $this->name . '/internal/', [
            RequestOptions::JSON => [
                'app_id' => $this->id,
                'app_secret' => $this->secret,
            ],
        ]);

        $ret = $this->handleResponse($response);

        $this->expireTime = $ret['expire'] + time();

        return $this->token = $ret[$this->name];
    }

    public function getId(): string
    {
        return $this->id;
    }

    protected function isExpired(): bool
    {
        return $this->expireTime <= time() + 60;
    }
}
