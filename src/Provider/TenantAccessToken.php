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

namespace HyperfX\Feishu\Provider;

use GuzzleHttp\RequestOptions;
use HyperfX\Feishu\AbstractProvider;
use Psr\Container\ContainerInterface;

class TenantAccessToken extends AbstractProvider
{
    /**
     * @var string
     */
    protected $name = 'tenantAccessToken';

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var int
     */
    protected $expireTime = 0;

    public function __construct(ContainerInterface $container, string $id, string $secret)
    {
        parent::__construct($container);
        $this->id = $id;
        $this->secret = $secret;
    }

    /**
     * 获取 TenantAccessToken.
     */
    public function getToken(bool $refresh = false): string
    {
        if (! $this->isExpired() && ! $refresh) {
            return $this->token;
        }

        $response = $this->client()->post('/open-apis/auth/v3/tenant_access_token/internal/', [
            RequestOptions::JSON => [
                'app_id' => $this->id,
                'app_secret' => $this->secret,
            ],
        ]);

        $ret = $this->handleResponse($response);

        $this->expireTime = $ret['expire'] + time();

        return $this->token = $ret['tenant_access_token'];
    }

    protected function isExpired(): bool
    {
        return $this->expireTime <= time() + 60;
    }
}
