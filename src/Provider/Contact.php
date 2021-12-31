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

class Contact extends AbstractProvider
{
    use TenantAccessTokenNeeded;

    protected string $name = 'Contact';

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
     * @param $extra = [
     *     'user_id_type' => 'open_id', // open_id, user_id, union_id
     *     'department_id_type' => 'open_department_id', // open_department_id, department_id
     * ]
     */
    public function department(string $id, array $extra = [])
    {
        $ret = $this->client()->get('open-apis/contact/v3/departments/' . $id, [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $this->getAccessToken()],
            RequestOptions::QUERY => $extra,
        ]);

        return $this->handleResponse($ret)['data'] ?? [];
    }

    /**
     * 获取子部门列表.
     * @param $extra = [
     *     'user_id_type' => 'open_id', // open_id, user_id, union_id
     *     'department_id_type' => 'open_department_id', // open_department_id, department_id
     *     'fetch_child' => false, // 是否递归获取子部门
     *     'page_size' => 10, // 分页大小
     *     'page_token' => '', // 分页TOKEN
     * ]
     */
    public function departmentChildren(string $id, array $extra = [])
    {
        $ret = $this->client()->get('open-apis/contact/v3/departments/' . $id . '/children', [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $this->getAccessToken()],
            RequestOptions::QUERY => $extra,
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
     * 获取部门下的用户.
     * @param $extra = [
     *     'user_id_type' => 'open_id', // open_id, user_id, union_id
     *     'department_id_type' => 'open_department_id', // open_department_id, department_id
     *     'page_size' => 10, // 分页大小
     *     'page_token' => '', // 分页TOKEN
     * ]
     */
    public function usersByDepartment(string $id, array $extra = [])
    {
        $ret = $this->client()->get('open-apis/contact/v3/users/find_by_department', [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $this->getAccessToken()],
            RequestOptions::QUERY => array_merge($extra, [
                'department_id' => $id,
            ]),
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
