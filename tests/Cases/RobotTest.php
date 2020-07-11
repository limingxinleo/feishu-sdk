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

use HyperfX\Feishu\Provider\Robots;

/**
 * @internal
 * @coversNothing
 */
class RobotTest extends AbstractTestCase
{
    public function testRobotGroupList()
    {
        $container = $this->getContainer();
        $provider = $container->get(Robots::class);

        $data = $provider->default->groupList();

        $this->assertArrayHasKey('groups', $data);
    }

    public function testRobotInfo()
    {
        $container = $this->getContainer();
        $provider = $container->get(Robots::class);

        $data = $provider->default->info();

        $this->assertArrayHasKey('open_id', $data);
    }

    public function testRobotSendText()
    {
        $container = $this->getContainer();
        $provider = $container->get(Robots::class);

        $data = $provider->default->sendText('oc_7b8ff4535f1ef58e65f833da8e808c1a', 'Hello Hyperf.');

        $this->assertArrayHasKey('message_id', $data);
    }
}
