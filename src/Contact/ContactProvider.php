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
namespace Fan\Feishu\Contact;

use Fan\Feishu\AccessToken\TenantAccessToken;
use Fan\Feishu\Http\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ContactProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple[Contact::getName()] = fn () => new Contact(
            $pimple[Client::getName()],
            $pimple[TenantAccessToken::getName()]
        );
    }
}
