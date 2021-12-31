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
use Fan\Feishu\AccessToken\AppAccessToken;
use Fan\Feishu\AccessToken\TenantAccessToken;
use Fan\Feishu\AccessTokenInterface;
use Fan\Feishu\AccessTokenNeeded;
use Psr\Container\ContainerInterface;

class Tenant extends AbstractProvider
{
    use AccessTokenNeeded;

    public Contact $contact;

    public Oauth $oauth;

    protected string $name = 'Tenant';

    public function __construct(ContainerInterface $container, protected array $conf)
    {
        parent::__construct($container);
        $token = $this->makeAccessToken(TenantAccessToken::class, $this->conf);
        $this->contact = tap(make(Contact::class), static function (Contact $provider) use ($token) {
            $provider->setAccessToken($token);
        });

        $token = $this->makeAccessToken(AppAccessToken::class, $this->conf);
        $this->oauth = tap(make(Oauth::class), static function (Oauth $provider) use ($token) {
            $provider->setAccessToken($token);
        });
    }

    private function makeAccessToken(string $class, array $conf): AccessTokenInterface
    {
        return make($class, [$this->container, $conf['app_id'], $conf['app_secret']]);
    }
}
