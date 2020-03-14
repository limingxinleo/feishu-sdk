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

    public function __construct(ContainerInterface $container, array $conf)
    {
        parent::__construct($container);
        $this->conf = $conf;
    }

    /**
     * 群组列表.
     */
    public function groupList(int $pageSize = 10, string $pageToken = null)
    {
        // $this->client()->
    }

    protected function getGuzzleConfig(): array
    {
        return parent::getGuzzleConfig();
    }
}
