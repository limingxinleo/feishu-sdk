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

/**
 * @property Contact\Contact $contact
 * @property Message\Message $message
 * @property Oauth\Oauth $oauth
 * @property Robot\Robot $robot
 * @property AccessToken\TenantAccessToken $tenant_access_token
 * @property AccessToken\AppAccessToken $app_access_token
 * @property Http\Client $http
 */
class Application
{
    private Container $container;

    private array $providers = [
        AccessToken\AccessTokenProvider::class,
        Contact\ContactProvider::class,
        Http\ClientProvider::class,
        Message\MessageProvider::class,
        Oauth\OauthProvider::class,
        Robot\RobotProvider::class,
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

        foreach ($this->providers as $provider) {
            $this->container->register(new $provider());
        }
    }

    public function __get(string $name)
    {
        return $this->container[$name] ?? null;
    }
}
