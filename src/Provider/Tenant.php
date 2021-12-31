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
namespace Fan\Feishu\Provider;

use Fan\Feishu\AbstractProvider;
use Fan\Feishu\TenantAccessTokenNeeded;
use Psr\Container\ContainerInterface;

class Tenant extends AbstractProvider
{
    use TenantAccessTokenNeeded;

    protected string $name = 'Tenant';

    protected Contact $contact;

    public function __construct(ContainerInterface $container, protected array $conf)
    {
        parent::__construct($container);
        $token = make(TenantAccessToken::class, [
            'id' => $conf['app_id'],
            'secret' => $conf['app_secret'],
        ]);

        $this->contact = tap(make(Contact::class), static function (Contact $provider) use ($token) {
            $provider->setTenantAccessToken($token);
        });
    }
}
