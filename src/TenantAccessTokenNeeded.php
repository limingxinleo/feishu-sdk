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

namespace HyperfX\Feishu;

trait TenantAccessTokenNeeded
{
    public function getAccessToken(): string
    {
        // $cached = $this->container->get(CacheInterface::class);
    }
}
