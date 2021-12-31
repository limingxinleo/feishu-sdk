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
use Fan\Feishu\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

class Tenants extends AbstractProvider
{
    protected string $name = 'Tenants';

    protected array $tenants = [];

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        foreach ($this->config->get('feishu.tenants', []) as $key => $item) {
            $this->tenants[$key] = make(Tenant::class, [
                'conf' => $item,
            ]);
        }
    }

    public function __get($name): Tenant
    {
        if (! isset($this->tenants[$name]) || ! $this->tenants[$name] instanceof Tenant) {
            throw new InvalidArgumentException("Tenant {$name} is invalid.");
        }

        return $this->tenants[$name];
    }
}
