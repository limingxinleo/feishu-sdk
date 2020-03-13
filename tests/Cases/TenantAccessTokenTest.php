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

namespace HyperfTest\Cases;

use Hyperf\Contract\ConfigInterface;
use HyperfX\Feishu\Provider\TenantAccessToken;

/**
 * @internal
 * @coversNothing
 */
class TenantAccessTokenTest extends AbstractTestCase
{
    public function testGetToken()
    {
        $container = $this->getContainer();
        $config = $container->get(ConfigInterface::class);
        $provider = new TenantAccessToken(
            $container,
            $config,
            $config->get('feishu.robots.default.app_id'),
            $config->get('feishu.robots.default.secret')
        );

        $token = $provider->getToken();
        $this->assertSame($token, $provider->getToken());
    }
}
