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

use Fan\Feishu\Factory;

/**
 * @internal
 * @coversNothing
 */
class HttpTest extends AbstractTestCase
{
    public function testTokenIsTimeout()
    {
        $factory = new Factory($this->getConfig());

        $app = $factory->get('default');

        $res = $app->contact->user('users_invalid_token');

        $this->assertArrayHasKey('user', $res);
    }
}
