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
use GuzzleHttp\RequestOptions;
use Psr\Container\ContainerInterface;

class Contact extends AbstractProvider
{
    use TenantAccessTokenNeeded;

    protected string $name = 'Contact';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * 获取单位信息.
     */
    public function unit(string $id)
    {
        $ret = $this->client()->get('open-apis/contact/v3/unit/' . $id, [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $this->getAccessToken()],
        ]);

        return $this->handleResponse($ret)['data'] ?? [];
    }

    /**
     * 获取部门信息.
     * @param $query = [
     *     'user_id_type' => 'open_id', // open_id, user_id, union_id
     *     'department_id_type' => 'open_department_id', // open_department_id, department_id
     * ]
     */
    public function department(string $id, array $query = [])
    {
        $ret = $this->client()->get('open-apis/contact/v3/departments/' . $id, [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $this->getAccessToken()],
            RequestOptions::QUERY => $query,
        ]);

        return $this->handleResponse($ret)['data'] ?? [];
    }

    /**
     * 获取用户信息.
     */
    public function user(string $id, string $type = 'open_id')
    {
        $ret = $this->client()->get('open-apis/contact/v3/users/' . $id, [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $this->getAccessToken()],
            RequestOptions::QUERY => [
                'user_id_type' => $type,
            ],
        ]);

        return $this->handleResponse($ret)['data'] ?? [];
    }

    /**
     * 批量获取用户ID.
     */
    public function batchGetUserId(array $mobiles = [], array $emails = [], string $type = 'open_id')
    {
        $ret = $this->client()->post('open-apis/contact/v3/users/batch_get_id', [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $this->getAccessToken()],
            RequestOptions::QUERY => [
                'user_id_type' => $type,
            ],
            RequestOptions::JSON => [
                'mobiles' => $mobiles,
                'emails' => $emails,
            ],
        ]);

        return $this->handleResponse($ret)['data'] ?? [];
    }
}
