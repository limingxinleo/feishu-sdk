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

use Pimple\Container;

class Application
{
    private Container $container;

    private array $providers = [
        AccessToken\AccessTokenProvider::class,
        Contact\ContactProvider::class,
        Http\ClientProvider::class,
        Oauth\OauthProvider::class,
    ];

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
        $config = new Config\Config($config);
        $this->container = new Container([
            'config' => $config,
        ]);
    }
}
