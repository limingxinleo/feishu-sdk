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

trait AccessTokenNeeded
{
    public ?AccessTokenInterface $token = null;

    public function setTenantAccessToken(AccessTokenInterface $token): void
    {
        $this->token = $token;
    }

    public function getAccessToken(): string
    {
        return $this->token->getToken();
    }
}
