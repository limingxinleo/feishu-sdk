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

use HyperfX\Feishu\Provider\TenantAccessToken;

trait TenantAccessTokenNeeded
{
    /**
     * @var TenantAccessToken
     */
    protected $token;

    public function init(string $id, string $secret)
    {
        $this->token = make(TenantAccessToken::class, [
            $this->container,
            $id,
            $secret,
        ]);
    }

    public function getAccessToken(): string
    {
        return $this->token->getToken();
    }
}
