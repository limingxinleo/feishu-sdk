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

/**
 * @internal
 * @coversNothing
 */
class TenantAccessTokenTest extends AbstractTestCase
{
    public function testGetToken()
    {
        $provider = $this->getFactory()->get('default')->tenant_access_token;

        $token = $provider->getToken();
        $this->assertSame($token, $provider->getToken());
    }
}
