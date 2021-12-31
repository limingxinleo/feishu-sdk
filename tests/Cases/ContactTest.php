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
class ContactTest extends AbstractTestCase
{
    public function testContactUser()
    {
        $container = $this->getContainer();
        $application = new Application($container);
        $res = $application->tenants->default->contact->user('SH0034');
        var_dump($res);
    }
}
