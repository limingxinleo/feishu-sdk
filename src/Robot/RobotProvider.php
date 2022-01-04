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
namespace Fan\Feishu\Robot;

use Fan\Feishu\AccessToken\TenantAccessToken;
use Fan\Feishu\Http\Client;
use Fan\Feishu\Message\Message;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class RobotProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple[Robot::getName()] = fn () => new Robot(
            $pimple[Client::getName()],
            $pimple[TenantAccessToken::getName()],
            $pimple[Message::getName()]
        );
    }
}
