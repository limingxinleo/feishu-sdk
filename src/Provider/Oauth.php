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
use Fan\Feishu\AccessTokenNeeded;
use GuzzleHttp\RequestOptions;

class Oauth extends AbstractProvider
{
    use AccessTokenNeeded;

    protected string $name = 'Oauth';

    /**
     * 获取重定向的URL.
     */
    public function authorize(string $uri, string $state = ''): string
    {
        return 'https://open.feishu.cn/open-apis/authen/v1/index?' . http_build_query([
            'app_id' => $this->token->getId(),
            'redirect_uri' => $uri,
            'state' => $state,
        ]);
    }

    public function getUserInfo(string $code): array
    {
        $ret = $this->client()->post('open-apis/authen/v1/access_token', [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $this->getAccessToken()],
            RequestOptions::JSON => [
                'grant_type' => 'authorization_code',
                'code' => $code,
            ],
        ]);

        return $this->handleResponse($ret)['data'] ?? [];
    }
}
