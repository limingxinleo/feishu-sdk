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

use Fan\Feishu\Config\Config;
use Fan\Feishu\Http\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AccessTokenProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple[AppAccessToken::getName()] = fn () => new AppAccessToken(
            $pimple[Config::getName()],
            $pimple[Client::getName()]
        );

        $pimple[TenantAccessToken::getName()] = fn () => new TenantAccessToken(
            $pimple[Config::getName()],
            $pimple[Client::getName()]
        );
    }
}
