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

use Fan\Feishu\Application;

/**
 * @internal
 * @coversNothing
 */
class OauthTest extends AbstractTestCase
{
    public function testOauthAuthorize()
    {
        $container = $this->getContainer();
        $application = new Application($container);

        $oauth = $application->tenants->default->oauth;
        $res = $oauth->authorize('http://127.0.0.1:9501/');

        var_dump($res);
        $this->assertSame('https://passport.feishu.cn/accounts/auth_login/oauth2/authorize?client_id=cli_a1442e36bcf95013&redirect_uri=http%3A%2F%2F127.0.0.1%3A9501%2F&state=&response_type=code', $res);
    }

    // public function testOauthGetUserInfo()
    // {
    //     $container = $this->getContainer();
    //     $application = new Application($container);
    //
    //     $oauth = $application->tenants->default->oauth;
    //     $res = $oauth->getUserInfo('babma61b84be41c1bc930bc5b69dd9ed');
    //
    //     var_dump($res);
    // }
}
