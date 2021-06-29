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
use Fan\Feishu\Provider\Message;
use Fan\Feishu\Provider\Robots;

/**
 * @internal
 * @coversNothing
 */
class ApplicationTest extends AbstractTestCase
{
    public function testApplication()
    {
        $application = new Application(
            $this->getContainer()
        );

        $this->assertInstanceOf(Message::class, $application->message);
        $this->assertInstanceOf(Robots::class, $application->robots);
    }
}
