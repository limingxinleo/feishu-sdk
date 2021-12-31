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

        $contact = $application->tenants->default->contact;

        $res = $contact->batchGetUserId(['18678000000']);
        $this->assertArrayHasKey('user_list', $res);

        $res = $application->tenants->default->contact->user('ou_983ee36cffcf4417884b0df4f3ff6918');
        $this->assertArrayHasKey('user', $res);
    }

    public function testContactDepartment()
    {
        $container = $this->getContainer();
        $application = new Application($container);

        $contact = $application->tenants->default->contact;

        $res = $contact->department('od-ff11e52d60abebad0ddd06572a6e9468');

        $this->assertArrayHasKey('department', $res);

        $res = $contact->departmentChildren('0', [
            'fetch_child' => true,
        ]);

        $this->assertArrayHasKey('has_more', $res);
    }
}
