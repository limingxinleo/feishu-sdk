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

namespace HyperfX\Feishu\Provider;

use Hyperf\Contract\ConfigInterface;
use HyperfX\Feishu\AbstractProvider;
use HyperfX\Feishu\TenantAccessTokenNeeded;
use Psr\Container\ContainerInterface;

class Robot extends AbstractProvider
{
    use TenantAccessTokenNeeded;

    /**
     * @var array
     */
    protected $conf;

    public function __construct(ContainerInterface $container, ConfigInterface $config, array $conf)
    {
        parent::__construct($container, $config);
        $this->conf = $conf;
    }

    public function groupList()
    {
        // $this->client()->
    }
}
