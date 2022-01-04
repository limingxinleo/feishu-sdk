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
use Fan\Feishu\Factory;

/**
 * @internal
 * @coversNothing
 */
class ApplicationTest extends AbstractTestCase
{
    public function testApplication()
    {
        $factory = new Factory($this->getConfig());

        $app = $factory->get('default');
        $this->assertInstanceOf(Application::class, $app);
    }
}
