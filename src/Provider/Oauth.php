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
namespace Fan\Feishu\Provider;

use Fan\Feishu\AbstractProvider;
use Fan\Feishu\TenantAccessTokenNeeded;

class Oauth extends AbstractProvider
{
    use TenantAccessTokenNeeded;

    protected string $name = 'Oauth';

    /**
     * 获取重定向的URL.
     */
    public function authorize(string $uri, string $state = ''): string
    {
        return 'https://passport.feishu.cn/accounts/auth_login/oauth2/authorize?' . http_build_query([
            'client_id' => $this->token->getId(),
            'redirect_uri' => $uri,
            'state' => $state,
            'response_type' => 'code',
        ]);
    }
}
