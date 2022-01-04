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
class OauthTest extends AbstractTestCase
{
    public function testOauthAuthorize()
    {
        $oauth = $this->getFactory()->get('default')->oauth;

        $res = $oauth->authorize('http://127.0.0.1:9501/');

        $this->assertSame('https://open.feishu.cn/open-apis/authen/v1/index?app_id=cli_a1442e36bcf95013&redirect_uri=http%3A%2F%2F127.0.0.1%3A9501%2F&state=', $res);
    }

    public function testOauthGetUserInfo()
    {
        $oauth = $this->getFactory()->get('default')->oauth;
        $res = $oauth->getUserInfo('UjLTMfspDt2Z8T80I39whd');
        $this->assertArrayHasKey('en_name', $res);
    }
}
