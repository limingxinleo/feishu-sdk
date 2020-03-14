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

use GuzzleHttp\RequestOptions;
use HyperfX\Feishu\AbstractProvider;
use HyperfX\Feishu\TenantAccessTokenNeeded;
use Psr\Container\ContainerInterface;

class Robot extends AbstractProvider
{
    use TenantAccessTokenNeeded;

    /**
     * @var string
     */
    protected $name = 'Robot';

    /**
     * @var array
     */
    protected $conf;

    public function __construct(ContainerInterface $container, array $conf)
    {
        parent::__construct($container);
        $this->conf = $conf;
        $this->init($conf['app_id'], $conf['app_secret']);
    }

    /**
     * 群组列表.
     */
    public function groupList(int $pageSize = 10, string $pageToken = null)
    {
        $query = ['page_size' => $pageSize];
        if ($pageToken) {
            $query['page_token'] = $pageToken;
        }

        $ret = $this->client()->get('/open-apis/chat/v4/list', [
            RequestOptions::HEADERS => [
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
            ],
            RequestOptions::QUERY => $query,
        ]);

        return $this->handleResponse($ret)['data'] ?? [];
    }
}
