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

use Hyperf\Utils\Codec\Json;

/**
 * @internal
 * @coversNothing
 */
class RobotTest extends AbstractTestCase
{
    public function testRobotGroupList()
    {
        $robot = $this->getFactory()->get('robot')->robot;

        $data = $robot->groupList();

        $this->assertArrayHasKey('groups', $data);
    }

    public function testRobotInfo()
    {
        $robot = $this->getFactory()->get('robot')->robot;

        $data = $robot->info();

        $this->assertArrayHasKey('open_id', $data);
    }

    public function testRobotSendText()
    {
        $robot = $this->getFactory()->get('robot')->robot;

        $data = $robot->sendText('oc_7b8ff4535f1ef58e65f833da8e808c1a', 'Hello Hyperf.');

        $this->assertArrayHasKey('message_id', $data);
    }

    public function testRobotSend()
    {
        $robot = $this->getFactory()->get('robot')->robot;

        $data = $robot->send([
            'receive_id' => 'oc_7b8ff4535f1ef58e65f833da8e808c1a',
            'msg_type' => 'text',
            'content' => ['text' => 'Hello Hyperf.'],
        ]);

        $this->assertArrayHasKey('message_id', $data);
    }

    public function testRobotSendMarkdown()
    {
        $robot = $this->getFactory()->get('robot')->robot;
        $data = $robot->send([
            'receive_id' => 'oc_7b8ff4535f1ef58e65f833da8e808c1a',
            'msg_type' => 'interactive',
            'content' => Json::decode(file_get_contents(__DIR__ . '/../json/req_send_interactive.json')),
        ]);

        $this->assertArrayHasKey('message_id', $data);
    }
}
